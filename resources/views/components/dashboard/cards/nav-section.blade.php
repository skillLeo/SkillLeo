{{-- ===========================================
     FILE 2: resources/views/components/dashboard/cards/nav-section.blade.php
     =========================================== --}}
     @props(['title', 'items'])

     <div class="nav-section">
         <div class="nav-section-title">{{ $title }}</div>
         <ul class="nav-menu">
             @foreach($items as $item)
                 <li>
                     <a href="{{ $item['url'] ?? '#' }}" 
                        class="{{ ($item['active'] ?? false) ? 'active' : '' }}">
                         <i class="fas {{ $item['icon'] }}"></i> {{ $item['label'] }}
                     </a>
                 </li>
             @endforeach
         </ul>
     </div>