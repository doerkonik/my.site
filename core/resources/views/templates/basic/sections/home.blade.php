@extends('layouts.app')

@section('content')

<style>
  /* ── Google Fonts ── */
  @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap');

  :root {
    --primary: #10b981;
    --primary-dark: #059669;
    --primary-glow: rgba(16,185,129,0.35);
    --teal: #14b8a6;
    --emerald: #34d399;
    --glass-bg: rgba(255,255,255,0.55);
    --glass-border: rgba(16,185,129,0.18);
    --glass-shadow: 0 8px 32px rgba(16,185,129,0.08);
    --text-dark: #0f172a;
    --text-muted: #64748b;
  }

  * { box-sizing: border-box; }

  body {
    font-family: 'DM Sans', sans-serif;
    background: #f0fdf8;
    color: var(--text-dark);
    overflow-x: hidden;
  }

  h1,h2,h3,h4 { font-family: 'Syne', sans-serif; }

  /* ── Utilities ── */
  .text-gradient {
    background: linear-gradient(135deg, var(--primary), var(--teal));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .glass {
    background: var(--glass-bg);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
  }

  .glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--glass-border);
    box-shadow: var(--glass-shadow);
  }

  .border-glow { border: 1px solid var(--glass-border); }

  .glow-primary {
    box-shadow: 0 0 24px var(--primary-glow), 0 4px 16px rgba(16,185,129,0.2);
  }

  /* ── Animations ── */
  @keyframes float {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-18px); }
  }
  @keyframes pulse-dot {
    0%,100% { opacity:1; transform:scale(1); }
    50% { opacity:.5; transform:scale(.8); }
  }
  @keyframes fadeUp {
    from { opacity:0; transform:translateY(28px); }
    to   { opacity:1; transform:translateY(0); }
  }
  @keyframes barGrow {
    from { height:0; }
    to   { height:var(--bar-h); }
  }

  .animate-float { animation: float 5s ease-in-out infinite; }
  .animate-float-2 { animation: float 6s ease-in-out infinite; animation-delay:.4s; }
  .animate-float-3 { animation: float 7s ease-in-out infinite; animation-delay:.8s; }
  .animate-pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }
  .fade-up { animation: fadeUp .7s ease both; }
  .fade-up-1 { animation: fadeUp .7s .1s ease both; }
  .fade-up-2 { animation: fadeUp .7s .2s ease both; }
  .fade-up-3 { animation: fadeUp .7s .3s ease both; }

  /* ── Orbs ── */
  .orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(72px);
    pointer-events: none;
    z-index: 0;
  }

  /* ── Container ── */
  .dtech-container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }

  /* ═══════════════════════════════
     HERO
  ═══════════════════════════════ */
  .hero {
    position: relative;
    min-height: 90vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    padding: 6rem 0;
  }

  .hero-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
  }

  @media (max-width:1024px) { .hero-grid { grid-template-columns:1fr; } .hero-card-wrap { display:none; } }

  .badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .4rem 1rem;
    border-radius: 999px;
    border: 1px solid rgba(16,185,129,.3);
    font-size: .75rem;
    font-weight: 700;
    color: var(--primary);
    background: rgba(16,185,129,.06);
    backdrop-filter: blur(10px);
    margin-bottom: 1.5rem;
    text-transform: uppercase;
    letter-spacing: .06em;
  }

  .hero h1 {
    font-size: clamp(2.8rem,6vw,4.5rem);
    font-weight: 800;
    line-height: 1.08;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
  }

  .hero p {
    font-size: 1.1rem;
    color: var(--text-muted);
    max-width: 32rem;
    line-height: 1.7;
    margin-bottom: 2rem;
  }

  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: 1rem 2rem;
    border-radius: 1rem;
    background: var(--primary);
    color: #fff;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: .875rem;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
    box-shadow: 0 0 24px var(--primary-glow);
    border: none;
    cursor: pointer;
  }
  .btn-primary:hover { transform: scale(1.05); color:#fff; }
  .btn-primary:active { transform: scale(.96); }

  .btn-glass {
    display: inline-flex;
    align-items: center;
    padding: 1rem 2rem;
    border-radius: 1rem;
    background: rgba(255,255,255,.55);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(16,185,129,.3);
    color: var(--primary);
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: .875rem;
    text-decoration: none;
    transition: background .2s;
  }
  .btn-glass:hover { background: rgba(16,185,129,.08); color:var(--primary); }

  .hero-stats { display:flex; gap:2.5rem; margin-top:3rem; }
  .stat-num { font-family:'Syne',sans-serif; font-size:1.5rem; font-weight:800; }
  .stat-label { font-size:.7rem; color:var(--text-muted); font-weight:600; margin-top:.1rem; }

  /* Hero Glass Card */
  .hero-card {
    border-radius: 1.5rem;
    padding: 1.5rem;
    max-width: 22rem;
    margin-left: auto;
  }

  .hero-card-dots { display:flex; gap:.5rem; margin-bottom:1.5rem; align-items:center; }
  .dot { width:12px; height:12px; border-radius:50%; }
  .dot-red { background:#f87171; }
  .dot-yellow { background:#fbbf24; }
  .dot-green { background:#4ade80; }
  .dot-label { font-size:.7rem; color:var(--text-muted); margin-left:.5rem; }

  .status-row {
    display:flex; align-items:center; justify-content:space-between;
    padding:.75rem 1rem; border-radius:.75rem;
    background:rgba(255,255,255,.5);
    margin-bottom:.5rem;
  }
  .status-name { display:flex; align-items:center; gap:.5rem; font-size:.8rem; font-weight:500; color:#334155; }
  .status-dot { width:8px; height:8px; border-radius:50%; }
  .status-dot-green { background:#4ade80; }
  .status-dot-blue { background:#60a5fa; }
  .status-val { font-size:.7rem; font-weight:700; color:var(--primary); }

  .usage-box { background:rgba(16,185,129,.08); border:1px solid rgba(16,185,129,.15); border-radius:1rem; padding:1rem; margin-top:.75rem; }
  .usage-label { font-size:.7rem; font-weight:700; color:var(--primary); margin-bottom:.5rem; }
  .bars { display:flex; align-items:flex-end; gap:3px; height:3rem; }
  .bar { flex:1; background:rgba(16,185,129,.4); border-radius:3px 3px 0 0; animation: barGrow .8s ease both; }

  /* ═══════════════════════════════
     SECTIONS
  ═══════════════════════════════ */
  .section { padding: 5rem 0; }

  .section-header { text-align:center; margin-bottom:3rem; }
  .section-header h2 { font-size:clamp(1.8rem,3.5vw,2.5rem); font-weight:800; color:var(--text-dark); margin-bottom:.6rem; }
  .section-header p { color:var(--text-muted); font-size:.95rem; max-width:36rem; margin:0 auto; }

  /* ═══════════════════════════════
     FEATURES GRID
  ═══════════════════════════════ */
  .features-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1.5rem; }

  .feature-card {
    border-radius:1.25rem;
    padding:1.5rem;
    transition: transform .3s, box-shadow .3s;
  }
  .feature-card:hover { transform:translateY(-4px); box-shadow:0 16px 40px rgba(16,185,129,.12); }

  .feature-icon {
    width:3rem; height:3rem; border-radius:.875rem;
    display:flex; align-items:center; justify-content:center;
    font-size:1.4rem; margin-bottom:1rem;
    transition:transform .3s;
  }
  .feature-card:hover .feature-icon { transform:scale(1.12); }

  .feature-card h3 { font-size:.95rem; font-weight:700; margin-bottom:.5rem; color:var(--text-dark); }
  .feature-card p { font-size:.8rem; color:var(--text-muted); line-height:1.65; margin:0; }

  /* ═══════════════════════════════
     HOSTING PLANS
  ═══════════════════════════════ */
  .plans-section { background:linear-gradient(to bottom, transparent, rgba(16,185,129,.04), transparent); }

  .plans-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; max-width:900px; margin:0 auto; }
  @media(max-width:768px) { .plans-grid { grid-template-columns:1fr; } }

  .plan-card {
    border-radius:1.25rem;
    padding:1.75rem;
    position:relative;
    transition:transform .3s, box-shadow .3s;
  }
  .plan-card:hover { transform:translateY(-5px); }
  .plan-card.popular { border-color:var(--primary) !important; box-shadow:0 0 32px var(--primary-glow); }

  .popular-badge {
    position:absolute; top:-12px; left:50%; transform:translateX(-50%);
    background:var(--primary); color:#fff;
    font-size:.65rem; font-weight:800; padding:.3rem .9rem;
    border-radius:999px; white-space:nowrap; text-transform:uppercase; letter-spacing:.05em;
  }

  .plan-name { font-size:.7rem; font-weight:700; color:var(--primary); text-transform:uppercase; letter-spacing:.08em; margin-bottom:.5rem; }
  .plan-price { font-family:'Syne',sans-serif; font-size:2rem; font-weight:800; color:var(--text-dark); }
  .plan-price span { font-size:.85rem; color:var(--text-muted); font-weight:400; }
  .plan-features { list-style:none; padding:0; margin:1.25rem 0 1.5rem; }
  .plan-features li { font-size:.8rem; color:var(--text-muted); padding:.4rem 0; border-bottom:1px solid rgba(16,185,129,.08); display:flex; align-items:center; gap:.5rem; }
  .plan-features li::before { content:''; width:6px; height:6px; border-radius:50%; background:var(--primary); flex-shrink:0; }

  /* ═══════════════════════════════
     VPS PLANS
  ═══════════════════════════════ */
  .vps-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:1rem; }

  .vps-card {
    border-radius:1.25rem;
    padding:1.25rem;
    transition:transform .3s;
  }
  .vps-card:hover { transform:translateY(-4px); }

  .vps-name { font-size:.65rem; font-weight:700; color:var(--primary); text-transform:uppercase; letter-spacing:.08em; margin-bottom:.5rem; }
  .vps-price { font-family:'Syne',sans-serif; font-size:1.5rem; font-weight:800; color:var(--text-dark); margin-bottom:1rem; }
  .vps-price span { font-size:.8rem; color:var(--text-muted); font-weight:400; }
  .vps-spec { font-size:.75rem; color:var(--text-muted); padding:.3rem 0; display:flex; align-items:center; gap:.4rem; }
  .vps-spec::before { content:''; width:4px; height:4px; border-radius:50%; background:var(--primary); flex-shrink:0; }

  /* ═══════════════════════════════
     CTA BANNER
  ═══════════════════════════════ */
  .cta-banner {
    position:relative;
    border-radius:1.75rem;
    overflow:hidden;
    padding:4rem 2rem;
    text-align:center;
    background:linear-gradient(135deg, var(--primary), var(--teal), #059669);
  }
  .cta-banner::before {
    content:'';
    position:absolute; inset:0;
    background:radial-gradient(ellipse at top right, rgba(255,255,255,.18), transparent 65%);
  }
  .cta-orb {
    position:absolute; bottom:-2rem; right:-2rem;
    width:10rem; height:10rem;
    background:rgba(255,255,255,.1);
    border-radius:50%; filter:blur(1.5rem);
  }
  .cta-banner h2 { font-size:clamp(1.8rem,3.5vw,2.5rem); font-weight:800; color:#fff; margin-bottom:1rem; position:relative; }
  .cta-banner p { color:rgba(255,255,255,.8); max-width:36rem; margin:0 auto 2rem; position:relative; font-size:.95rem; }
  .cta-actions { display:flex; justify-content:center; gap:1rem; flex-wrap:wrap; position:relative; }

  .btn-white {
    padding:.875rem 2rem; border-radius:1rem;
    background:#fff; color:var(--primary);
    font-family:'Syne',sans-serif; font-weight:800; font-size:.875rem;
    text-decoration:none;
    transition:transform .2s, box-shadow .2s;
    box-shadow:0 8px 24px rgba(0,0,0,.15);
  }
  .btn-white:hover { transform:scale(1.05); color:var(--primary); }

  .btn-outline-white {
    padding:.875rem 2rem; border-radius:1rem;
    background:rgba(255,255,255,.2);
    border:1px solid rgba(255,255,255,.35);
    color:#fff; font-family:'Syne',sans-serif; font-weight:700; font-size:.875rem;
    text-decoration:none;
    transition:background .2s;
  }
  .btn-outline-white:hover { background:rgba(255,255,255,.3); color:#fff; }

  /* icon bg helpers */
  .bg-yellow { background:#fef3c7; } .bg-blue { background:#dbeafe; } .bg-green { background:#d1fae5; }
  .bg-purple { background:#ede9fe; } .bg-orange { background:#ffedd5; } .bg-teal { background:#ccfbf1; }
</style>

{{-- ═══════════════ HERO ═══════════════ --}}
<section class="hero">
  {{-- orbs --}}
  <div class="orb animate-float"   style="width:24rem;height:24rem;top:5rem;left:20%;background:rgba(16,185,129,.18);"></div>
  <div class="orb animate-float-2" style="width:16rem;height:16rem;bottom:5rem;right:20%;background:rgba(20,184,166,.12);"></div>
  <div class="orb animate-float-3" style="width:12rem;height:12rem;top:10rem;right:28%;background:rgba(52,211,153,.08);"></div>

  <div class="dtech-container" style="position:relative;z-index:1;width:100%;">
    <div class="hero-grid">

      {{-- Left --}}
      <div class="fade-up">
        <div class="badge">
          <span class="status-dot status-dot-green animate-pulse-dot"></span>
          Host Any App. Any Stack. Instantly.
        </div>
        <h1>
          Premium Cloud
          <span class="text-gradient d-block">Hosting for</span>
          Bangladesh
        </h1>
        <p>Deploy Node.js, Python, PHP, .NET, Next.js, React &amp; more on high-performance infrastructure with one-click deployment and 24/7 support.</p>
        <div class="d-flex flex-wrap gap-3">
          <a href="{{ route('user.register') }}" class="btn-primary">Get Started Free →</a>
          <a href="{{ route('home') }}#domain" class="btn-glass">Search Domain</a>
        </div>
        <div class="hero-stats">
          @foreach([['10K+','Happy Clients'],['99.9%','Uptime SLA'],['24/7','Support']] as $s)
          <div>
            <div class="stat-num text-gradient">{{ $s[0] }}</div>
            <div class="stat-label">{{ $s[1] }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Right: Dashboard Card --}}
      <div class="hero-card-wrap fade-up-1">
        <div class="hero-card glass-card animate-float-2">
          <div class="hero-card-dots">
            <div class="dot dot-red"></div>
            <div class="dot dot-yellow"></div>
            <div class="dot dot-green"></div>
            <span class="dot-label">dtech-dashboard</span>
          </div>
          @foreach([['Web Server','Running','status-dot-green'],['Database','Active','status-dot-green'],['SSL Certificate','Valid','status-dot-green'],['CDN','Enabled','status-dot-blue']] as $row)
          <div class="status-row">
            <div class="status-name">
              <span class="status-dot {{ $row[2] }} animate-pulse-dot"></span>
              {{ $row[0] }}
            </div>
            <span class="status-val">{{ $row[1] }}</span>
          </div>
          @endforeach
          <div class="usage-box">
            <div class="usage-label">Monthly Usage</div>
            <div class="bars">
              @foreach([40,65,45,80,60,90,70] as $h)
              <div class="bar" style="--bar-h:{{ $h }}%; height:{{ $h }}%; animation-delay:{{ $loop->index * 0.08 }}s;"></div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ═══════════════ FEATURES ═══════════════ --}}
<section class="section">
  <div class="dtech-container">
    <div class="section-header fade-up">
      <h2>Everything You Need to <span class="text-gradient">Scale Fast</span></h2>
      <p>World-class infrastructure with Bangladesh-local support.</p>
    </div>
    <div class="features-grid">
      @php
        $features = [
          ['⚡','Instant Deployment','Go live in seconds with our one-click deployment system. Zero configuration headaches.','bg-yellow'],
          ['🛡️','Enterprise Security','DDoS protection, WAF, free SSL, and daily backups keep your data safe 24/7.','bg-blue'],
          ['🌍','Bangladesh CDN','Local edge nodes ensure blazing-fast load times for your Bangladeshi audience.','bg-green'],
          ['📊','Real-time Analytics','Monitor bandwidth, requests, and uptime from your intuitive control panel.','bg-purple'],
          ['🔄','Auto Backups','Daily automated backups with one-click restoration. Never lose your data again.','bg-orange'],
          ['💬','24/7 Local Support','Bangla-speaking support team available round the clock via WhatsApp and chat.','bg-teal'],
        ];
      @endphp
      @foreach($features as $f)
      <div class="feature-card glass-card fade-up-{{ ($loop->index % 3) + 1 }}">
        <div class="feature-icon {{ $f[3] }}">{{ $f[0] }}</div>
        <h3>{{ $f[1] }}</h3>
        <p>{{ $f[2] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ═══════════════ HOSTING PLANS ═══════════════ --}}
<section class="section plans-section">
  <div class="dtech-container">
    <div class="section-header fade-up">
      <h2>Hosting Plans <span class="text-gradient">Built for Speed</span></h2>
      <p>Transparent pricing with everything you need.</p>
    </div>
    <div class="plans-grid">
      @php
        $plans = [
          ['Starter','700',['1 Website','5 GB SSD','10 GB Bandwidth','Free SSL','24/7 Support'], false],
          ['Business','1,200',['5 Websites','20 GB SSD','Unlimited Bandwidth','Free SSL','Daily Backup','Priority Support'], true],
          ['Pro','2,000',['Unlimited Sites','50 GB SSD','Unlimited Bandwidth','Free SSL','Daily Backup','Dedicated IP'], false],
        ];
      @endphp
      @foreach($plans as $plan)
      <div class="plan-card glass-card {{ $plan[3] ? 'popular' : '' }}">
        @if($plan[3]) <div class="popular-badge">Most Popular</div> @endif
        <div class="plan-name">{{ $plan[0] }}</div>
        <div class="plan-price">৳{{ $plan[1] }}<span>/yr</span></div>
        <ul class="plan-features">
          @foreach($plan[2] as $feat)
          <li>{{ $feat }}</li>
          @endforeach
        </ul>
        <a href="{{ route('user.register') }}" class="{{ $plan[3] ? 'btn-primary' : 'btn-glass' }}" style="width:100%;justify-content:center;">
          Get Started
        </a>
      </div>
      @endforeach
    </div>
    <div class="text-center mt-5">
      <a href="{{ route('home') }}#hosting" class="btn-glass">View All Plans →</a>
    </div>
  </div>
</section>


{{-- ═══════════════ VPS PLANS ═══════════════ --}}
<section class="section">
  <div class="dtech-container">
    <div class="section-header fade-up">
      <h2>Blazing-Fast <span class="text-gradient">VPS Hosting</span></h2>
      <p>Full root access, guaranteed resources, and instant provisioning.</p>
    </div>
    <div class="vps-grid">
      @php
        $vpsPlans = [
          ['VPS Nano','1,200','1 vCPU','1 GB RAM','20 GB SSD','1 TB/mo'],
          ['VPS Micro','2,000','2 vCPU','2 GB RAM','40 GB SSD','2 TB/mo'],
          ['VPS Pro','3,500','4 vCPU','4 GB RAM','80 GB SSD','4 TB/mo'],
          ['VPS Ultra','6,000','8 vCPU','8 GB RAM','160 GB SSD','8 TB/mo'],
        ];
      @endphp
      @foreach($vpsPlans as $vps)
      <div class="vps-card glass-card fade-up-{{ ($loop->index % 3) + 1 }}">
        <div class="vps-name">{{ $vps[0] }}</div>
        <div class="vps-price">৳{{ $vps[1] }}<span>/mo</span></div>
        @foreach(array_slice($vps, 2) as $spec)
        <div class="vps-spec">{{ $spec }}</div>
        @endforeach
      </div>
      @endforeach
    </div>
    <div class="text-center mt-5">
      <a href="{{ route('home') }}#vps" class="btn-primary">Explore VPS Plans →</a>
    </div>
  </div>
</section>


{{-- ═══════════════ CTA BANNER ═══════════════ --}}
<section class="section">
  <div class="dtech-container">
    <div class="cta-banner fade-up">
      <div class="cta-orb"></div>
      <h2>Ready to Go Live?</h2>
      <p>Join thousands of Bangladeshi businesses running on dTech's lightning-fast infrastructure.</p>
      <div class="cta-actions">
        <a href="{{ route('user.register') }}" class="btn-white">Start Free Today</a>
        <a href="{{ route('home') }}#domain" class="btn-outline-white">Search Domain</a>
      </div>
    </div>
  </div>
</section>

@endsection