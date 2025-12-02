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

/**
 * Check whether a string is a syntactically valid email address.
 * @param {string} email - The email address to validate.
 * @returns {boolean} `true` if the string matches a basic email pattern, `false` otherwise.
 */
function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i;
  return emailRegex.test(email);
}

/**
 * Shows an inline error for a form field.
 *
 * Adds an 'error' class to the supplied form control and appends a sibling
 * <span class="error-message"> containing the provided message.
 *
 * @param {HTMLElement} field - The form control element (input, textarea, or select) to annotate.
 * @param {string} message - The error message to display next to the field.
 */
function showFieldError(field, message) {
  if (!field) return;
  clearFieldError(field);
  field.classList.add('error');
  const errorElement = document.createElement('span');
  errorElement.className = 'error-message';
  errorElement.textContent = message;
  field.parentElement.appendChild(errorElement);
}

/**
 * Remove visual error state and any inline error message associated with a form field.
 * @param {HTMLElement|null|undefined} field - The form control element whose error state should be cleared; no action if falsy.
 */
function clearFieldError(field) {
  if (!field) return;
  field.classList.remove('error');
  const existing = field.parentElement.querySelector('.error-message');
  if (existing) existing.remove();
}

/**
 * Selects the first DOM element that matches the given CSS selector.
 * @returns {Element|null} The first Element that matches `sel`, or `null` if no match.
 */
function getElement(sel) {
  return document.querySelector(sel);
}

/**
 * Get all elements that match a CSS selector as an array.
 * @param {string} sel - CSS selector string used to query the document.
 * @return {Element[]} An array of matching Element objects; empty if no matches.
 */
function getElements(sel) {
  return Array.from(document.querySelectorAll(sel));
}

/**
 * Normalize a category value into a lowercase, trimmed string with internal whitespace collapsed.
 * @param {*} text - Value to normalize; coerced to a string if not already one.
 * @returns {string} The normalized text: trimmed, lowercased, and consecutive whitespace collapsed to single spaces.
 */
function normalizeCategoryText(text = '') {
  return text.toString().trim().toLowerCase().replace(/\s+/g, ' ');
}

/**
 * Checks whether the given form contains any required inputs, textareas, or selects with empty values.
 * @param {HTMLFormElement} form - The form element to inspect.
 * @returns {boolean} `true` if any required field is empty (after trimming), `false` otherwise.
 */
function hasEmptyFields(form) {
  const requiredInputs = form.querySelectorAll('input[required], textarea[required], select[required]');
  for (let input of requiredInputs) {
    if (!input.value.trim()) return true;
  }
  return false;
}

/**
 * Validate the login form's email and password inputs and block submission if validation fails.
 * Shows inline field errors and a required-field alert when appropriate; prevents the event's default action on failure.
 * @param {Event} e - The submit event from the login form.
 * @returns {boolean} `true` if validation passes, `false` otherwise.
 */
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

/**
 * Validate registration form fields and show inline error messages when invalid.
 *
 * Performs required-field checks, email format validation, password length verification,
 * and password/confirmation matching; displays inline errors for invalid fields and prevents
 * the form submission when validation fails.
 * @param {Event} e - Submit event from the registration form.
 * @returns {boolean} `true` if the form passed validation, `false` otherwise.
 */
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

/**
 * Validate the contact form, display inline error messages or a success message, and reset the form on success.
 *
 * Performs required-field checks, email format validation, scrolls to the first error when present,
 * and either shows an inline success result element (if present) or falls back to an alert. Prevents the
 * form's default submit behavior.
 *
 * @param {Event} e - Submit event from the contact form.
 * @returns {boolean} `true` if validation passed and the form was processed and reset, `false` otherwise.
 */
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

/**
 * Attach realtime inline validation to a form's inputs and textareas.
 *
 * Adds listeners that clear inline errors while the user types and display inline
 * required/email validation messages when a field loses focus; valid fields have
 * their inline errors removed.
 *
 * @param {HTMLFormElement|Element} form - The form element to wire realtime validation onto.
 */
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

/**
 * Determines whether a card's category text satisfies the currently selected category filter.
 *
 * Compares normalized forms of the card category and the selected category value; when a mapping for the selected value exists in APP_CONFIG.categoryMapping, that mapped label is considered as an additional match candidate. An empty or falsy selectedCategoryValue always matches.
 * @param {string} cardCategoryText - Category text extracted from the opportunity card (may be empty or undefined).
 * @param {string} selectedCategoryValue - The selected category filter value (internal key or display text).
 * @returns {boolean} `true` if the card should be shown for the selected category, `false` otherwise.
 */
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

/**
 * Toggle the no-results message inside the opportunities grid.
 * @param {boolean} show - `true` to insert the configured no-results message into the grid, `false` to remove any existing no-results message.
 */
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

/**
 * Filter visible opportunity cards using the current search input and selected category.
 *
 * Reads the search term and selected category from the configured selectors, hides cards whose
 * title or description do not include the search term or whose category does not match the
 * selected category, updates each card's display and `hidden-card` class, and toggles the
 * no-results message based on whether any cards remain visible.
 */
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

/**
 * Populate opportunity detail fields in the DOM from a matching entry identified by the `id` URL parameter.
 *
 * Looks up an opportunity in the global `detailedOpportunities` array using the numeric `id` query parameter.
 * If a matching entry is found, updates a fixed set of DOM elements (title, description, date, location, category,
 * datetime, location detail, organizer, requirements) using predefined selectors. If `id` is missing or no entry is found,
 * the function does nothing.
 */
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

/**
 * Toggle visibility of the mobile navigation menu and update hamburger state.
 *
 * Updates CSS active classes on the mobile menu and hamburger button and sets the hamburger's
 * `aria-expanded` attribute to reflect the new open/closed state.
 */
function toggleMobileMenu() {
  const navMenu = getElement(APP_CONFIG.selectors.mobileMenu);
  const hamburger = getElement(APP_CONFIG.selectors.hamburger);
  if (!navMenu || !hamburger) return;
  const expanded = hamburger.getAttribute('aria-expanded') === 'true';
  navMenu.classList.toggle('active');
  hamburger.classList.toggle('active');
  hamburger.setAttribute('aria-expanded', (!expanded).toString());
}

/**
 * Close the mobile navigation menu when the user clicks outside the navigation container.
 * @param {Event} event - The click event used to detect whether the target is outside the navigation container.
 */
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

/**
 * Initializes the back-to-top button: shows it when the page is scrolled past 300px and scrolls the viewport to the top when clicked.
 *
 * Attaches scroll and click listeners to the configured back-to-top element if present.
 */
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

/**
 * Attach validation behavior to available forms on the page.
 *
 * Adds the `novalidate` attribute and wires submit handlers and realtime validation to
 * the login, register, and contact forms when present. For the edit-profile form,
 * adds `novalidate` and a basic submit-time required-field check that blocks submission
 * and shows an alert if any required fields are empty.
 */
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

/**
 * Wires search and filter UI controls to trigger opportunity filtering.
 *
 * Attaches click, Enter-key, and change listeners to the configured search button, search input, category filter, and date filter so that filterOpportunities runs when the user interacts with those controls. Missing elements are ignored. 
 */
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

/**
 * Initialize the opportunity detail view when the current path indicates the opportunity detail page.
 *
 * If the URL path contains "opportunity_detail.php", loads and populates the opportunity's details into the DOM.
 */
function initOpportunityDetail() {
  if (window.location.pathname.includes('opportunity_detail.php')) {
    loadOpportunityDetail();
  }
}

/**
 * Initialize profile page UI and interactions.
 *
 * Currently a no-op; profile-specific interactions are provided by global handlers so no additional
 * event listeners are attached here by default.
 */
function initProfilePage() {
  // Using the new window.toggleOpportunities logic in Section 7, 
  // so no event listener needed here unless using a different ID.
}

/**
 * Initialize mobile navigation event handlers.
 *
 * Attaches a click listener to the hamburger button to toggle the mobile menu and
 * a global click listener to close the mobile menu when clicking outside the navigation.
 */
function initMobileNavigation() {
  const hamburger = getElement(APP_CONFIG.selectors.hamburger);
  if (hamburger) hamburger.addEventListener('click', toggleMobileMenu);
  document.addEventListener('click', closeMobileMenuOnClickOutside);
}

/**
 * Initialize application UI behavior and attach global event listeners.
 *
 * Wires up navigation, form validation, search and filters, opportunity detail loading,
 * profile interactions, and the back-to-top control so the page becomes interactive.
 */
function init() {
  console.log('Community Volunteer Hub initialized');
  initMobileNavigation();
  initFormValidation();
  initSearchAndFilters();
  initOpportunityDetail();
  initProfilePage();
  initBackToTop(); // Start Back to Top logic
}

/**
 * Toggle visibility of the user's opportunities list and update related UI state.
 *
 * Shows the list and hides the placeholder message when the list is currently hidden; hides the list and shows the placeholder message when the list is currently visible. Also updates the toggle button's text content and button styling.
 *
 * Targets elements with IDs 'opportunities-list-wrapper', 'hidden-message', and 'toggle-btn'.
 */
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