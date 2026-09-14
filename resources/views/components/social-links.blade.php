@props([
  'variant' => 'list'
])

@php
  $socials = [
    [
      'platform' => 'facebook',
      'title' => 'Facebook Resmi PMA',
      'label' => 'Facebook',
      'icon' => 'bi bi-facebook',
      'iconClass' => 'profil-social-icon--facebook',
      'url' => $siteSettings['social_facebook'] ?? 'https://web.facebook.com/PT-Prolabios-Mitra-Analitika-1787666991553394/'
    ],
    [
      'platform' => 'instagram',
      'title' => 'Instagram @prolabios.id',
      'label' => 'Instagram',
      'icon' => 'bi bi-instagram',
      'iconClass' => 'profil-social-icon--instagram',
      'url' => $siteSettings['social_instagram'] ?? 'https://www.instagram.com/prolabios.id'
    ],
    [
      'platform' => 'linkedin',
      'title' => 'LinkedIn Company Page',
      'label' => 'LinkedIn',
      'icon' => 'bi bi-linkedin',
      'iconClass' => 'profil-social-icon--linkedin',
      'url' => $siteSettings['social_linkedin'] ?? 'https://www.linkedin.com/company/pt-prolabios-mitra-analitika/posts/?feedView=all'
    ],
    [
      'platform' => 'whatsapp',
      'title' => 'WhatsApp Layanan Resmi',
      'label' => 'WhatsApp',
      'icon' => 'bi bi-whatsapp',
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
          <i class="{{ $item['icon'] }} {{ $item['iconClass'] }} fs-5"></i>
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
           class="footer-social-link" 
           aria-label="{{ $item['label'] }}"
           title="{{ $item['title'] }}">
          <i class="{{ $item['icon'] }}" aria-hidden="true"></i>
        </a>
      @endif
    @endforeach
  </div>
@endif
