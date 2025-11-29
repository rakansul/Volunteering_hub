
/* 1. CONFIG & CONSTANTS */
const APP_CONFIG = {
  selectors: {
    searchBtn: '.btn-search',
    searchInput: '#search-input',
    categoryFilter: '#category-filter',
    dateFilter: '#date-filter',
    mobileMenu: '.nav-menu',
    hamburger: '.hamburger'
  },
  messages: {
    validation: {
      required: 'هذا الحقل مطلوب',
      emailRequired: 'البريد الإلكتروني مطلوب',
      emailInvalid: 'الرجاء إدخال بريد إلكتروني صحيح',
      passwordRequired: 'كلمة المرور مطلوبة',
      passwordLength: 'يجب أن لا تقل كلمة المرور عن 6 أحرف',
      passwordMatch: 'الرجاء تأكيد كلمة المرور',
      passwordMismatch: 'كلمتا المرور غير متطابقتين',
      nameFirst: 'الاسم الأول مطلوب',
      nameLast: 'الاسم الأخير مطلوب',
      nameFull: 'الاسم الكامل مطلوب',
      subject: 'الموضوع مطلوب',
      message: 'الرسالة مطلوبة'
    },
    registration: {
      success: (title) => `تم التسجيل بنجاح في "${title}"! شكراً لك على التطوع. سيتم التواصل معك قريباً.`,
      buttonSuccess: 'تم التسجيل ✓',
      notFoundTitle: 'الفرصة غير موجودة',
      notFoundDesc: 'عذراً، لم يتم العثور على هذه الفرصة. يرجى العودة إلى صفحة الفرص.'
    },
    contact: {
      success: 'شكراً لك! تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.'
    },
    profile: {
      empty: 'ليس لديك أي فرص مسجلة. تصفح الفرص لتبدأ!',
      loadBtnActive: 'إخفاء فُرصي',
      loadBtnInactive: 'عرض فُرصي',
      hiddenMsg: 'انقر على "عرض فُرصي" لعرض الفرص المسجل بها.'
    },
    search: {
      noResults: 'لم يتم العثور على نتائج. جرب تعديل معايير البحث.'
    }
  },
  categoryMapping: {
    environment: 'البيئة',
    entertainment: 'الترفيه',
    tourism: 'السياحة',
    tech: 'التقنية',
    education: 'التعليم',
    healthcare: 'الرعاية الصحية',
    community: 'المجتمع'
  }
};

/* 3. HELPERS */
function validateEmail(email) {
  // Improved but still reasonably permissive regex. Back-end must re-validate.
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i;
  return emailRegex.test(email);
}

function showFieldError(field, message) {
  if (!field) return;
  clearFieldError(field);
  field.classList.add('error');
  const errorElement = document.createElement('span');
  errorElement.className = 'error-message';
  errorElement.textContent = message;
  field.parentElement.appendChild(errorElement);
}

function clearFieldError(field) {
  if (!field) return;
  field.classList.remove('error');
  const existing = field.parentElement.querySelector('.error-message');
  if (existing) existing.remove();
}

function getElement(sel) {
  return document.querySelector(sel);
}
function getElements(sel) {
  return Array.from(document.querySelectorAll(sel));
}

function normalizeCategoryText(text = '') {
  // Trim, lowercase and remove extra spaces/diacritics if needed (simple normalization)
  return text.toString().trim().toLowerCase().replace(/\s+/g, ' ');
}

/* 4. FORM VALIDATION (login/register/contact) */
function validateLoginForm(e) {
  const form = e.target;
  const email = form.querySelector('input[type="email"]');
  const password = form.querySelector('input[type="password"]');
  let ok = true;
  clearFieldError(email);
  clearFieldError(password);

  if (!email.value.trim()) {
    showFieldError(email, APP_CONFIG.messages.validation.emailRequired);
    ok = false;
  } else if (!validateEmail(email.value.trim())) {
    showFieldError(email, APP_CONFIG.messages.validation.emailInvalid);
    ok = false;
  }
  if (!password.value.trim()) {
    showFieldError(password, APP_CONFIG.messages.validation.passwordRequired);
    ok = false;
  }

  if (!ok) e.preventDefault();
  return ok;
}

function validateRegisterForm(e) {
  const form = e.target;
  const first = form.querySelector('[name="first_name"], #first-name');
  const last = form.querySelector('[name="last_name"], #last-name');
  const email = form.querySelector('input[type="email"]');
  const password = form.querySelector('[name="password"], #password');
  const confirm = form.querySelector('[name="confirm_password"], #confirm-password');

  let ok = true;
  [first, last, email, password, confirm].forEach(clearFieldError);

  if (!first || !first.value.trim()) {
    showFieldError(first, APP_CONFIG.messages.validation.nameFirst); ok = false;
  }
  if (!last || !last.value.trim()) {
    showFieldError(last, APP_CONFIG.messages.validation.nameLast); ok = false;
  }
  if (!email || !email.value.trim()) {
    showFieldError(email, APP_CONFIG.messages.validation.emailRequired); ok = false;
  } else if (!validateEmail(email.value.trim())) {
    showFieldError(email, APP_CONFIG.messages.validation.emailInvalid); ok = false;
  }
  if (!password || !password.value.trim()) {
    showFieldError(password, APP_CONFIG.messages.validation.passwordRequired); ok = false;
  } else if (password.value.length < 8) {
    showFieldError(password, APP_CONFIG.messages.validation.passwordLength); ok = false;
  }
  if (!confirm || !confirm.value.trim()) {
    showFieldError(confirm, APP_CONFIG.messages.validation.passwordMatch); ok = false;
  } else if (password.value !== confirm.value) {
    showFieldError(confirm, APP_CONFIG.messages.validation.passwordMismatch); ok = false;
  }

  if (!ok) e.preventDefault();
  return ok;
}

function validateContactForm(e) {
  // Preventing default here is ok because contact form is handled via JS in your original file.
  e.preventDefault();
  const form = e.target;
  const name = form.querySelector('#contact-name');
  const email = form.querySelector('#contact-email');
  const subject = form.querySelector('#contact-subject');
  const message = form.querySelector('#contact-message');

  let ok = true;
  [name, email, subject, message].forEach(clearFieldError);

  if (!name.value.trim()) { showFieldError(name, APP_CONFIG.messages.validation.nameFull); ok = false; }
  if (!email.value.trim()) { showFieldError(email, APP_CONFIG.messages.validation.emailRequired); ok = false; }
  else if (!validateEmail(email.value.trim())) { showFieldError(email, APP_CONFIG.messages.validation.emailInvalid); ok = false; }
  if (!subject.value.trim()) { showFieldError(subject, APP_CONFIG.messages.validation.subject); ok = false; }
  if (!message.value.trim()) { showFieldError(message, APP_CONFIG.messages.validation.message); ok = false; }

  if (!ok) {
    const firstError = form.querySelector('.error');
    if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return false;
  }

  // If you prefer email-only contact, send via server. For demo, show success message client-side:
  const resultDiv = getElement('#contact-message-result');
  if (resultDiv) {
    resultDiv.textContent = APP_CONFIG.messages.contact.success;
    resultDiv.className = 'registration-message success';
    resultDiv.style.display = 'block';
    form.reset();
    setTimeout(() => {
      resultDiv.style.display = 'none';
      resultDiv.textContent = '';
      resultDiv.className = 'registration-message';
    }, 5000);
  } else {
    alert(APP_CONFIG.messages.contact.success);
    form.reset();
  }

  return true;
}

function setupRealtimeValidation(form) {
  if (!form) return;
  form.addEventListener('input', (ev) => {
    const target = ev.target;
    if (target.matches('input, textarea')) {
      if (target.classList.contains('error')) clearFieldError(target);
    }
  });

  form.addEventListener('blur', (ev) => {
    const input = ev.target;
    if (!input.matches('input, textarea')) return;
    const value = (input.value || '').trim();
    if (input.required && !value) {
      if (input.type === 'email') showFieldError(input, APP_CONFIG.messages.validation.emailRequired);
      else showFieldError(input, APP_CONFIG.messages.validation.required);
      return;
    }
    if (input.type === 'email' && value && !validateEmail(value)) {
      showFieldError(input, APP_CONFIG.messages.validation.emailInvalid);
      return;
    }
    clearFieldError(input);
  }, true); // useCapture so blur is caught per element
}

/* 5. SEARCH & FILTERS */
function categoryMatches(cardCategoryText, selectedCategoryValue) {
  if (!selectedCategoryValue) return true;
  const cardNorm = normalizeCategoryText(cardCategoryText || '');
  const selected = normalizeCategoryText(selectedCategoryValue || '');
  // try direct Arabic match
  if (cardNorm.includes(selected)) return true;
  // try mapping english slug -> arabic then compare
  const mapped = APP_CONFIG.categoryMapping[selectedCategoryValue] || APP_CONFIG.categoryMapping[selected];
  if (mapped && normalizeCategoryText(mapped).includes(cardNorm)) return true;
  // try using mapping reverse: if selected value is slug, compare card text to mapped Arabic
  if (mapped && normalizeCategoryText(cardCategoryText).includes(normalizeCategoryText(mapped))) return true;
  return false;
}

function toggleNoResultsMessage(show) {
  const container = getElement('.opportunities-grid');
  if (!container) return;
  const existing = container.querySelector('.no-results-message');
  if (existing) existing.remove();
  if (show) {
    const div = document.createElement('div');
    div.className = 'no-results-message';
    div.innerHTML = `<h3>${APP_CONFIG.messages.search.noResults}</h3>`;
    container.appendChild(div);
  }
}

function filterOpportunities() {
  const searchInput = getElement(APP_CONFIG.selectors.searchInput);
  const categoryFilter = getElement(APP_CONFIG.selectors.categoryFilter);
  const opportunityCards = getElements('.opportunity-card');
  if (!opportunityCards.length) return;
  const term = (searchInput?.value || '').trim().toLowerCase();
  const selectedCategory = categoryFilter?.value || '';
  let found = false;

  opportunityCards.forEach(card => {
    const title = (card.querySelector('h3')?.textContent || '').toLowerCase();
    const desc = (card.querySelector('p')?.textContent || '').toLowerCase();
    const cat = (card.querySelector('.category')?.textContent || '').toLowerCase();

    const matchesSearch = !term || title.includes(term) || desc.includes(term);
    const matchesCategory = categoryMatches(cat, selectedCategory);

    if (matchesSearch && matchesCategory) {
      card.style.display = '';
      // rely on CSS transitions; keep style changes minimal
      card.classList.remove('hidden-card');
      found = true;
    } else {
      card.style.display = 'none';
      card.classList.add('hidden-card');
    }
  });

  toggleNoResultsMessage(!found);
}

/* 6. OPPORTUNITY DETAIL (client-side populate for static demo pages) */
function loadOpportunityDetail() {
  const url = new URL(window.location.href);
  const id = parseInt(url.searchParams.get('id') || '0', 10);
  if (!id) return;
  // find in demo data (only for static demonstrations)
  const opp = (detailedOpportunities || []).find(o => o.id === id);
  if (!opp) {
    const titleEl = getElement('#opportunity-title');
    const descEl = getElement('#opportunity-description');
    if (titleEl) titleEl.textContent = APP_CONFIG.messages.registration.notFoundTitle;
    if (descEl) descEl.textContent = APP_CONFIG.messages.registration.notFoundDesc;
    return;
  }
  const map = {
    title: '#opportunity-title',
    description: '#opportunity-description',
    date: '#opportunity-date',
    location: '#opportunity-location',
    category: '#opportunity-category',
    datetime: '#opportunity-datetime',
    locationDetail: '#opportunity-location-detail',
    organizer: '#opportunity-organizer',
    requirements: '#opportunity-requirements'
  };
  Object.keys(map).forEach(k => {
    const el = getElement(map[k]);
    if (!el) return;
    el.textContent = opp[k] || opp[k === 'category' ? 'category' : 'description'] || '-';
  });
  // IMPORTANT: do NOT intercept the real registration <form> submission.
  // If you want a confirm-before-submit UX, attach an onsubmit handler to the form,
  // not to the button, and do not preventDefault unless you explicitly want to block the server call.
}

/* 7. PROFILE - dynamic rendering of registered events (client-side demo) */
function createRegisteredOpportunityItem(opp, index = 0) {
  const article = document.createElement('article');
  article.className = 'opportunity-item';
  article.innerHTML = `
    <div class="item-content">
      <h4>${escapeHtml(opp.title)}</h4>
      <div class="item-meta">
        <span class="date">${escapeHtml(opp.date)}</span>
        <span class="location">${escapeHtml(opp.location)}</span>
      </div>
    </div>
  `;
  const cancelBtn = document.createElement('button');
  cancelBtn.className = 'btn btn-danger';
  cancelBtn.textContent = 'إلغاء التسجيل';
  cancelBtn.addEventListener('click', () => {
    if (!confirm(`هل أنت متأكد من رغبتك في إلغاء التسجيل في "${opp.title}"؟`)) return;
    // If using server side, you should call API to cancel (fetch POST to cancel endpoint)
    article.remove();
    const container = getElement('.opportunities-list-profile');
    if (container && container.children.length === 0) {
      container.innerHTML = `<p class="empty-message">${APP_CONFIG.messages.profile.empty}</p>`;
    }
  });
  article.appendChild(cancelBtn);
  return article;
}

function toggleRegisteredOpportunities() {
  const container = getElement('.opportunities-list-profile');
  const loadBtn = getElement('#load-events-btn');
  if (!container || !loadBtn) return;
  const isExpanded = loadBtn.classList.contains('active');
  if (isExpanded) {
    container.innerHTML = `<p class="empty-message">${APP_CONFIG.messages.profile.hiddenMsg}</p>`;
    loadBtn.textContent = APP_CONFIG.messages.profile.loadBtnInactive;
    loadBtn.classList.remove('active');
  } else {
    container.innerHTML = '';
    if (!mockRegisteredOpportunities || mockRegisteredOpportunities.length === 0) {
      container.innerHTML = `<p class="empty-message">${APP_CONFIG.messages.profile.empty}</p>`;
    } else {
      mockRegisteredOpportunities.forEach((o, idx) => {
        container.appendChild(createRegisteredOpportunityItem(o, idx));
      });
    }
    loadBtn.textContent = APP_CONFIG.messages.profile.loadBtnActive;
    loadBtn.classList.add('active');
  }
}

/* 8. NAV & UI */
function toggleMobileMenu() {
  const navMenu = getElement(APP_CONFIG.selectors.mobileMenu);
  const hamburger = getElement(APP_CONFIG.selectors.hamburger);
  if (!navMenu || !hamburger) return;
  const expanded = hamburger.getAttribute('aria-expanded') === 'true';
  navMenu.classList.toggle('active');
  hamburger.classList.toggle('active');
  hamburger.setAttribute('aria-expanded', (!expanded).toString());
}

function closeMobileMenuOnClickOutside(event) {
  const navMenu = getElement(APP_CONFIG.selectors.mobileMenu);
  const hamburger = getElement(APP_CONFIG.selectors.hamburger);
  const navContainer = getElement('.nav-container');
  if (!navMenu || !navContainer || !hamburger) return;
  // If nav is open and click is outside nav-container, close it
  if (navMenu.classList.contains('active') && !navContainer.contains(event.target)) {
    navMenu.classList.remove('active');
    hamburger.classList.remove('active');
    hamburger.setAttribute('aria-expanded', 'false');
  }
}

/* 9. INIT / ATTACH EVENTS */
function initMobileNavigation() {
  const hamburger = getElement(APP_CONFIG.selectors.hamburger);
  if (hamburger) hamburger.addEventListener('click', toggleMobileMenu);
  document.addEventListener('click', closeMobileMenuOnClickOutside);
}

function initFormValidation() {
  // login form (server-side action)
  const loginForm = document.querySelector('form[action="login_action.php"], form[action="login.php"], form[action="login_action.php"], form[action="login_action.php"]');
  if (loginForm) {
    loginForm.addEventListener('submit', validateLoginForm);
    setupRealtimeValidation(loginForm);
  }
  // register form
  const registerForm = document.querySelector('form[action="register_action.php"], form[action="register.php"]');
  if (registerForm) {
    registerForm.addEventListener('submit', validateRegisterForm);
    setupRealtimeValidation(registerForm);
  }
  // contact form (if client-side)
  const contactForm = getElement('#contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', validateContactForm);
    setupRealtimeValidation(contactForm);
  }
}

function initSearchAndFilters() {
  const searchBtn = getElement(APP_CONFIG.selectors.searchBtn);
  const searchInput = getElement(APP_CONFIG.selectors.searchInput);
  const categoryFilter = getElement(APP_CONFIG.selectors.categoryFilter);
  const dateFilter = getElement(APP_CONFIG.selectors.dateFilter);

  if (searchBtn) searchBtn.addEventListener('click', filterOpportunities);
  if (searchInput) {
    searchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        filterOpportunities();
      }
    });
  }
  if (categoryFilter) categoryFilter.addEventListener('change', filterOpportunities);
  if (dateFilter) dateFilter.addEventListener('change', filterOpportunities);
}

function initOpportunityDetail() {
  // Populate the page for static demo mode (doesn't block real server-side forms)
  if (window.location.pathname.includes('opportunity_detail.php') || window.location.pathname.includes('opportunity_detail.html')) {
    loadOpportunityDetail();
  }
}

function initProfilePage() {
  const loadBtn = getElement('#load-events-btn');
  if (loadBtn) loadBtn.addEventListener('click', toggleRegisteredOpportunities);
}

/* 10. STARTUP */
function init() {
  console.log('Community Volunteer Hub initialized');
  initMobileNavigation();
  initFormValidation();
  initSearchAndFilters();
  initOpportunityDetail();
  initProfilePage();
}

/* small utility used above */
function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
}

/* Run on DOM ready */
document.addEventListener('DOMContentLoaded', init);

