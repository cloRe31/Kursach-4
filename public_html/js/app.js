/**
 * Harbor - Main App
 */
(function() {
  'use strict';

  // === MOBILE MENU ===
  const burger = document.getElementById('burger');
  const mobileMenu = document.getElementById('mobileMenu');
  
  if (burger && mobileMenu) {
    const toggleMenu = (open) => {
      burger.classList.toggle('active', open);
      burger.setAttribute('aria-expanded', open);
      mobileMenu.classList.toggle('active', open);
      mobileMenu.setAttribute('aria-hidden', !open);
      document.body.classList.toggle('no-scroll', open);
    };

    burger.addEventListener('click', () => {
      toggleMenu(!mobileMenu.classList.contains('active'));
    });
    
    mobileMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => toggleMenu(false));
    });

    // Закрытие по Escape
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
        toggleMenu(false);
      }
    });
  }

  // === MODAL ===
  const modal = document.getElementById('callbackModal');
  const modalCar = document.getElementById('modalCar');
  const modalForm = modal?.querySelector('.modal__form');
  const modalSuccess = modal?.querySelector('.modal__success');

  window.openModal = (car = '') => {
    if (!modal) return;
    if (modalCar) modalCar.value = car;
    if (modalForm) modalForm.style.display = 'block';
    if (modalSuccess) modalSuccess.style.display = 'none';
    modal.classList.add('active');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('no-scroll');
    // Фокус на первый input
    setTimeout(() => modal.querySelector('input')?.focus(), 100);
  };

  window.closeModal = () => {
    if (!modal) return;
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('no-scroll');
  };

  document.querySelectorAll('[data-modal="callback"]').forEach(btn => {
    btn.addEventListener('click', () => openModal(btn.dataset.car || ''));
  });

  modal?.querySelector('.modal__overlay')?.addEventListener('click', closeModal);
  modal?.querySelector('.modal__close')?.addEventListener('click', closeModal);
  document.addEventListener('keydown', e => e.key === 'Escape' && closeModal());

  // === FORMS ===
  document.querySelectorAll('form[data-type]').forEach(form => {
    form.addEventListener('submit', async e => {
      e.preventDefault();
      const btn = form.querySelector('button[type="submit"]');
      const originalText = btn.innerHTML;
      
      btn.disabled = true;
      btn.innerHTML = '<span class="material-icons spinning">sync</span>';

      try {
        const res = await fetch('/public_html/api/lead.php', {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: new FormData(form)
        });
        const data = await res.json();

        if (data.ok || data.success) {
          if (form.closest('.modal')) {
            modalForm.style.display = 'none';
            modalSuccess.style.display = 'block';
            setTimeout(closeModal, 3000);
          } else {
            btn.innerHTML = '<span class="material-icons">check</span> Отправлено';
            btn.style.background = '#22c55e';
            form.reset();
            setTimeout(() => {
              btn.innerHTML = originalText;
              btn.style.background = '';
              btn.disabled = false;
            }, 3000);
          }
        } else {
          throw new Error();
        }
      } catch {
        btn.innerHTML = '<span class="material-icons">error</span> Ошибка';
        btn.style.background = '#ef4444';
        setTimeout(() => {
          btn.innerHTML = originalText;
          btn.style.background = '';
          btn.disabled = false;
        }, 3000);
      }
    });
  });

  // === PHONE MASK ===
  document.querySelectorAll('input[type="tel"]').forEach(input => {
    input.addEventListener('input', e => {
      let v = e.target.value.replace(/\D/g, '');
      if (v[0] === '8') v = '7' + v.slice(1);
      if (v[0] !== '7' && v.length) v = '7' + v;
      
      let f = '';
      if (v.length > 0) f = '+' + v[0];
      if (v.length > 1) f += ' (' + v.slice(1, 4);
      if (v.length > 4) f += ') ' + v.slice(4, 7);
      if (v.length > 7) f += '-' + v.slice(7, 9);
      if (v.length > 9) f += '-' + v.slice(9, 11);
      e.target.value = f;
    });

    input.addEventListener('focus', e => {
      if (!e.target.value) e.target.value = '+7 (';
    });

    input.addEventListener('blur', e => {
      if (e.target.value === '+7 (') e.target.value = '';
    });
  });

  // === SMOOTH SCROLL ===
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const id = a.getAttribute('href');
      if (id === '#') return;
      const el = document.querySelector(id);
      if (el) {
        e.preventDefault();
        const offset = document.querySelector('.header')?.offsetHeight || 0;
        window.scrollTo({
          top: el.offsetTop - offset - 20,
          behavior: 'smooth'
        });
      }
    });
  });

  // === COLOR SELECTOR ===
  document.querySelectorAll('.color-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const name = document.getElementById('colorName');
      if (name) name.textContent = btn.dataset.name;
    });
  });

  // === SPINNER ANIMATION ===
  const style = document.createElement('style');
  style.textContent = `
    @keyframes spin { from { transform: rotate(0) } to { transform: rotate(360deg) } }
    .spinning { animation: spin 1s linear infinite; }
  `;
  document.head.appendChild(style);
})();
