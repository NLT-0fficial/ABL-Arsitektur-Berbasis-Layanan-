/**
 * IndeKos - Main JavaScript
 * Fitur: Smooth scroll, dropdown navbar, modal popup, animated counter
 */

// ==================== SMOOTH SCROLL NAVIGATION ====================
const navLinks = document.querySelectorAll('.nav-link:not(.dropdown-toggle)');
const homeSection = document.getElementById('home');
const aboutSection = document.getElementById('about-section');
const loginSection = document.getElementById('login');
const ctaButton = document.getElementById('cta-about');

// Fungsi smooth scroll ke elemen
function scrollToElement(element) {
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}

// Event listener untuk menu utama (Home, Login) dan dropdown items
document.querySelectorAll('.nav-link, .dropdown-item').forEach(link => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    const targetId = link.getAttribute('href').substring(1);
    let target = null;
    
    if (targetId === 'home') target = homeSection;
    else if (targetId === 'about-section') target = aboutSection;
    else if (targetId === 'login') target = loginSection;
    else if (targetId === 'system-section') target = document.getElementById('system-section');
    else if (targetId === 'kamar-section') target = document.getElementById('kamar-section');
    
    if (target) {
      scrollToElement(target);
      // Update active class pada navbar utama (Home/About/Login)
      updateActiveNavLink(targetId);
    }
  });
});

// Update active class pada menu navbar (Home, About, Login)
function updateActiveNavLink(sectionId) {
  // Reset semua active di nav-link utama
  document.querySelectorAll('.nav-link:not(.dropdown-toggle)').forEach(link => {
    link.classList.remove('active');
  });
  // Set active berdasarkan section
  if (sectionId === 'home') {
    document.querySelector('.nav-link[href="#home"]').classList.add('active');
  } else if (sectionId === 'about-section' || sectionId === 'system-section' || sectionId === 'kamar-section') {
    document.querySelector('.nav-link[href="#about-section"]').classList.add('active');
  } else if (sectionId === 'login') {
    document.querySelector('.nav-link[href="#login"]').classList.add('active');
  }
}

// CTA "Lihat Kamar" scroll ke bagian Kamar
if (ctaButton) {
  ctaButton.addEventListener('click', (e) => {
    e.preventDefault();
    const kamarSection = document.getElementById('kamar-section');
    if (kamarSection) {
      scrollToElement(kamarSection);
      updateActiveNavLink('about-section');
    }
  });
}

// Brand click scroll ke home
document.querySelector('.nav-brand').addEventListener('click', () => {
  scrollToElement(homeSection);
  updateActiveNavLink('home');
});

// ==================== POPUP MODAL UNTUK FITUR SYSTEM ====================
const featureCards = document.querySelectorAll('.feature-card');
const modal = document.getElementById('featureModal');
const modalIcon = modal.querySelector('.modal-icon');
const modalTitle = modal.querySelector('.modal-title');
const modalDesc = modal.querySelector('.modal-desc');
const modalClose = modal.querySelector('.modal-close');

// Data detail setiap fitur
const featureDetails = {
  akses: {
    icon: '🔐',
    title: 'Akses Pintu Digital',
    desc: 'Sistem barcode unik per penyewa untuk mencatat log keluar-masuk kost secara otomatis dan bisa dipantau orang tua kapan saja.'
  },
  tagihan: {
    icon: '💳',
    title: 'Tagihan Otomatis',
    desc: 'Mengkalkulasi biaya sewa termasuk biaya AC secara otomatis & terintegrasi dengan Payment Gateway terpercaya.'
  },
  keluhan: {
    icon: '📋',
    title: 'Laporan Keluhan',
    desc: 'Penyewa dapat melapor kerusakan langsung dari aplikasi dan admin siap untuk memproses dan memperbarui status real-time.'
  },
  manajemen: {
    icon: '🏠',
    title: 'Manajemen Kamar',
    desc: 'Data lengkap spesifikasi, fasilitas, dan status kamar. Kelola ketersediaan dengan mudah.'
  },
  ortu: {
    icon: '👁️',
    title: 'Monitoring Orang Tua',
    desc: 'Memantau riwayat keluar-masuk anak secara real-time melalui log akses pintu yang tersimpan otomatis.'
  }
};

// Buka modal dengan data fitur tertentu
function openModal(featureKey) {
  const data = featureDetails[featureKey];
  if (!data) return;
  modalIcon.textContent = data.icon;
  modalTitle.textContent = data.title;
  modalDesc.textContent = data.desc;
  modal.classList.add('active');
}

// Tutup modal
function closeModal() {
  modal.classList.remove('active');
}

// Pasang event listener pada setiap card fitur
featureCards.forEach(card => {
  card.addEventListener('click', (e) => {
    e.stopPropagation();
    const feature = card.dataset.feature;
    openModal(feature);
  });
});

// Tutup modal jika klik di background modal atau tombol close
modal.addEventListener('click', (e) => {
  // Klik pada background modal (bukan pada .modal-content) akan menutup
  if (e.target === modal || e.target === modalClose) {
    closeModal();
  }
});

// ==================== ANIMATED COUNTER (STATISTIK UNIT KAMAR) ====================
function animateCounter(el, target, duration = 1200) {
  let start = 0;
  const step = target / (duration / 16);
  const timer = setInterval(() => {
    start += step;
    if (start >= target) {
      el.textContent = target;
      clearInterval(timer);
    } else {
      el.textContent = Math.floor(start);
    }
  }, 16);
}

function runCounters() {
  const unitEl = document.querySelector('.stat-item:first-child .stat-number');
  if (unitEl && unitEl.dataset.animated !== 'true') {
    unitEl.dataset.animated = 'true';
    animateCounter(unitEl, 72);
  }
}

// Jalankan counter saat halaman dimuat
runCounters();