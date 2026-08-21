#!/usr/bin/env bash
input=$(cat)

cwd=$(echo "$input" | jq -r '.cwd // .workspace.current_dir // empty')
model=$(echo "$input" | jq -r '.model.display_name // empty')
used=$(echo "$input" | jq -r '.context_window.used_percentage // empty')

# Shorten home directory to ~
home="$HOME"
[ -n "$home" ] && cwd="${cwd/#$home/~}"

parts=""

# Directory
[ -n "$cwd" ] && parts="\033[1;34m$cwd\033[0m"

# Model
[ -n "$model" ] && parts="$parts \033[0;90m|\033[0m \033[0;36m$model\033[0m"

# Context usage (only after first message)
if [ -n "$used" ]; then
  used_int=$(printf '%.0f' "$used")
  if [ "$used_int" -ge 80 ]; then
    color="\033[0;31m"
  elif [ "$used_int" -ge 50 ]; then
    color="\033[0;33m"
  else
    color="\033[0;32m"
  fi
  parts="$parts \033[0;90m|\033[0m ${color}ctx:${used_int}%\033[0m"
fi

printf "%b" "$parts"
