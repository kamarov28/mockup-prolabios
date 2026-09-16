@props([
  'variant' => 'list'
])

@php
  $socials = [
    [
      'platform' => 'facebook',
      'title' => 'Facebook Resmi PMA',
      'label' => 'Facebook',
      'iconClass' => 'profil-social-icon--facebook',
      'url' => $siteSettings['social_facebook'] ?? 'https://web.facebook.com/PT-Prolabios-Mitra-Analitika-1787666991553394/'
    ],
    [
      'platform' => 'instagram',
      'title' => 'Instagram @prolabios.id',
      'label' => 'Instagram',
      'iconClass' => 'profil-social-icon--instagram',
      'url' => $siteSettings['social_instagram'] ?? 'https://www.instagram.com/prolabios.id'
    ],
    [
      'platform' => 'linkedin',
      'title' => 'LinkedIn Company Page',
      'label' => 'LinkedIn',
      'iconClass' => 'profil-social-icon--linkedin',
      'url' => $siteSettings['social_linkedin'] ?? 'https://www.linkedin.com/company/pt-prolabios-mitra-analitika/posts/?feedView=all'
    ],
    [
      'platform' => 'whatsapp',
      'title' => 'WhatsApp Layanan Resmi',
      'label' => 'WhatsApp',
      'iconClass' => 'profil-social-icon--whatsapp',
      'url' => !empty($siteSettings['contact_whatsapp']) 
                ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $siteSettings['contact_whatsapp']) 
                : 'https://wa.me/6281211118744'
    ]
  ];

@endphp

@if($variant === 'list')
  <div {{ $attributes->merge(['class' => 'd-flex flex-column gap-2']) }}>
    @foreach($socials as $item)
      @if(!empty($item['url']))
        <a href="{{ $item['url'] }}"
           target="_blank" 
           rel="noopener noreferrer" 
           class="profil-social-link">
          <span class="d-inline-flex align-items-center justify-content-center {{ $item['iconClass'] }}" style="width: 24px; height: 24px;">
            <x-brand-icon :name="$item['platform']" size="20" />
          </span>
          <span>{{ $item['title'] }}</span>
        </a>
      @endif
    @endforeach
  </div>
@elseif($variant === 'pills')
  <div {{ $attributes->merge(['class' => 'footer-social-strip d-flex gap-3']) }}>
    @foreach($socials as $item)
      @if(!empty($item['url']))
        <a href="{{ $item['url'] }}" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="footer-social-link d-inline-flex align-items-center justify-content-center" 
           aria-label="{{ $item['label'] }}"
           title="{{ $item['title'] }}">
          <x-brand-icon :name="$item['platform']" size="16" />
        </a>
      @endif
    @endforeach
  </div>
@endif
