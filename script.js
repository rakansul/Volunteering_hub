/* 1. CONFIG & CONSTANTS */
const APP_CONFIG = {
  selectors: {
    searchBtn: '.btn-search',
    searchInput: '#search-input',
    categoryFilter: '#category-filter',
    dateFilter: '#date-filter',
    mobileMenu: '.nav-menu',
    hamburger: '.hamburger',
    backToTop: '#backToTop' // Added for Back to Top button
  },
  messages: {
    validation: {
      required: 'هذا الحقل مطلوب',
      emailRequired: 'البريد الإلكتروني مطلوب',
      emailInvalid: 'الرجاء إدخال بريد إلكتروني صحيح',
      passwordRequired: 'كلمة المرور مطلوبة',
      passwordLength: 'يجب أن لا تقل كلمة المرور عن 8 أحرف',
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
  return text.toString().trim().toLowerCase().replace(/\s+/g, ' ');
}

// Helper: Check if form has any empty required fields
function hasEmptyFields(form) {
  const requiredInputs = form.querySelectorAll('input[required], textarea[required], select[required]');
  for (let input of requiredInputs) {
    if (!input.value.trim()) return true;
  }
  return false;
}

/* 4. FORM VALIDATION (login/register/contact) */
function validateLoginForm(e) {
  const form = e.target;
  const email = form.querySelector('input[type="email"]');
  const password = form.querySelector('input[type="password"]');
  let ok = true;

  clearFieldError(email);
  clearFieldError(password);

  // 1. Alert if any required field is empty (Shows Popup)
  if (hasEmptyFields(form)) {
    alert("Please fill out all required fields.");
    ok = false;
  }

  // 2. Validate specific fields (Shows Inline Red Error)
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
  const first = form.querySelector('[name="first_name"]');
  const last = form.querySelector('[name="last_name"]');
  const email = form.querySelector('input[type="email"]');
  const password = form.querySelector('[name="password"]');
  const confirm = form.querySelector('[name="confirm_password"]');

  let ok = true;
  [first, last, email, password, confirm].forEach(clearFieldError);

  // Validate Fields (Shows Inline Red Errors)
  if (!first.value.trim()) { showFieldError(first, APP_CONFIG.messages.validation.nameFirst); ok = false; }
  if (!last.value.trim()) { showFieldError(last, APP_CONFIG.messages.validation.nameLast); ok = false; }
  if (!email.value.trim()) { showFieldError(email, APP_CONFIG.messages.validation.emailRequired); ok = false; }
  else if (!validateEmail(email.value.trim())) { showFieldError(email, APP_CONFIG.messages.validation.emailInvalid); ok = false; }
  if (!password.value.trim()) { showFieldError(password, APP_CONFIG.messages.validation.passwordRequired); ok = false; }
  else if (password.value.length < 8) { showFieldError(password, APP_CONFIG.messages.validation.passwordLength); ok = false; }
  if (!confirm.value.trim()) { showFieldError(confirm, APP_CONFIG.messages.validation.passwordMatch); ok = false; }
  else if (password.value !== confirm.value) { showFieldError(confirm, APP_CONFIG.messages.validation.passwordMismatch); ok = false; }

  if (!ok) e.preventDefault();
  return ok;
}

function validateContactForm(e) {
  e.preventDefault();
  const form = e.target;
  const name = form.querySelector('#contact-name');
  const email = form.querySelector('#contact-email');
  const subject = form.querySelector('#contact-subject');
  const message = form.querySelector('#contact-message');

  let ok = true;
  [name, email, subject, message].forEach(clearFieldError);

  // 1. Alert if empty (Shows Popup)
  if (hasEmptyFields(form)) {
    alert("Please fill out all required fields.");
    ok = false;
  }

  // 2. Validate Fields (Shows Inline Red Errors)
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

  // Success path
  const resultDiv = getElement('#contact-message-result');
  if (resultDiv) {
    resultDiv.textContent = APP_CONFIG.messages.contact.success;
    resultDiv.className = 'registration-message success';
    resultDiv.style.display = 'block';
    form.reset();
    setTimeout(() => {
      resultDiv.style.display = 'none';
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
    
    // Only show inline error on blur, do not alert
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
  }, true); 
}

/* 5. SEARCH & FILTERS */
function categoryMatches(cardCategoryText, selectedCategoryValue) {
  if (!selectedCategoryValue) return true;
  const cardNorm = normalizeCategoryText(cardCategoryText || '');
  const selected = normalizeCategoryText(selectedCategoryValue || '');
  if (cardNorm.includes(selected)) return true;
  const mapped = APP_CONFIG.categoryMapping[selectedCategoryValue] || APP_CONFIG.categoryMapping[selected];
  if (mapped && normalizeCategoryText(mapped).includes(cardNorm)) return true;
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
      card.classList.remove('hidden-card');
      found = true;
    } else {
      card.style.display = 'none';
      card.classList.add('hidden-card');
    }
  });

  toggleNoResultsMessage(!found);
}

/* 6. OPPORTUNITY DETAIL */
function loadOpportunityDetail() {
  const url = new URL(window.location.href);
  const id = parseInt(url.searchParams.get('id') || '0', 10);
  if (!id) return;
  
  // This function is for static demo data. 
  // In your PHP setup, the data comes from the server, so this might not find anything unless 'detailedOpportunities' exists.
  const opp = (typeof detailedOpportunities !== 'undefined') ? detailedOpportunities.find(o => o.id === id) : null;
  if (!opp) return; 

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
}

/* 7. PROFILE (Show/Hide Opportunities) */
// Attached to window so onclick="..." works in HTML
window.toggleOpportunities = function() {
  const listWrapper = document.getElementById('opportunities-list-wrapper');
  const msgWrapper = document.getElementById('hidden-message');
  const btn = document.getElementById('toggle-btn');

  if (!listWrapper || !msgWrapper || !btn) return;

  if (listWrapper.style.display === 'none') {
    // Show List
    listWrapper.style.display = 'block';
    msgWrapper.style.display = 'none';
    btn.textContent = 'إخفاء فُرصي';
    btn.classList.remove('btn-primary');
    btn.classList.add('btn-secondary');
  } else {
    // Hide List
    listWrapper.style.display = 'none';
    msgWrapper.style.display = 'block';
    btn.textContent = 'عرض فُرصي';
    btn.classList.remove('btn-secondary');
    btn.classList.add('btn-primary');
  }
};

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
  if (navMenu.classList.contains('active') && !navContainer.contains(event.target)) {
    navMenu.classList.remove('active');
    hamburger.classList.remove('active');
    hamburger.setAttribute('aria-expanded', 'false');
  }
}

function initBackToTop() {
  const backToTopBtn = getElement(APP_CONFIG.selectors.backToTop);
  if (!backToTopBtn) return;

  // Show/Hide on Scroll
  window.addEventListener('scroll', () => {
    if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
      backToTopBtn.style.display = "flex";
    } else {
      backToTopBtn.style.display = "none";
    }
  });

  // Scroll Up on Click
  backToTopBtn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

/* 9. INIT / ATTACH EVENTS */
function initFormValidation() {
  // Login form: add 'novalidate' to fix alert blocking
  const loginForm = document.querySelector('form[action="login_action.php"], form[action="login.php"]');
  if (loginForm) {
    loginForm.setAttribute('novalidate', 'novalidate');
    loginForm.addEventListener('submit', validateLoginForm);
    setupRealtimeValidation(loginForm);
  }
  
  // Register form: add 'novalidate'
  const registerForm = document.querySelector('form[action="register_action.php"], form[action="register.php"]');
  if (registerForm) {
    registerForm.setAttribute('novalidate', 'novalidate');
    registerForm.addEventListener('submit', validateRegisterForm);
    setupRealtimeValidation(registerForm);
  }
  
  // Contact form: add 'novalidate'
  const contactForm = getElement('#contact-form');
  if (contactForm) {
    contactForm.setAttribute('novalidate', 'novalidate');
    contactForm.addEventListener('submit', validateContactForm);
    setupRealtimeValidation(contactForm);
  }

  // Edit Profile form: add 'novalidate' and basic check
  const editProfileForm = document.querySelector('form[action="edit_profile_action.php"]');
  if (editProfileForm) {
    editProfileForm.setAttribute('novalidate', 'novalidate');
    editProfileForm.addEventListener('submit', (e) => {
      if (hasEmptyFields(e.target)) {
        e.preventDefault();
        alert("Please fill out all required fields.");
      }
    });
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
  if (window.location.pathname.includes('opportunity_detail.php')) {
    loadOpportunityDetail();
  }
}

function initProfilePage() {
  // Using the new window.toggleOpportunities logic in Section 7, 
  // so no event listener needed here unless using a different ID.
}

function initMobileNavigation() {
  const hamburger = getElement(APP_CONFIG.selectors.hamburger);
  if (hamburger) hamburger.addEventListener('click', toggleMobileMenu);
  document.addEventListener('click', closeMobileMenuOnClickOutside);
}

/* 10. STARTUP */
function init() {
  console.log('Community Volunteer Hub initialized');
  initMobileNavigation();
  initFormValidation();
  initSearchAndFilters();
  initOpportunityDetail();
  initProfilePage();
  initBackToTop(); // Start Back to Top logic
}

function toggleOpportunities() {
    const listWrapper = document.getElementById('opportunities-list-wrapper');
    const msgWrapper = document.getElementById('hidden-message');
    const btn = document.getElementById('toggle-btn');
    if (listWrapper.style.display === 'none') {
        // ACTION: SHOW THE LIST
        listWrapper.style.display = 'block';     // Show list
        msgWrapper.style.display = 'none';       // Hide text message        
        btn.textContent = 'إخفاء فُرصي';         // Change text to "Hide"        
        // Switch button color to Green (Secondary)
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-secondary'); 
    } else {
        // ACTION: HIDE THE LIST
        listWrapper.style.display = 'none';      // Hide list
        msgWrapper.style.display = 'block';      // Show text message        
        btn.textContent = 'عرض فُرصي';           // Change text to "Show"        
        // Switch button color back to Blue (Primary)
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-primary');
    }
}

document.addEventListener('DOMContentLoaded', init);
