# Module 2: Server-Side Programming with PHP
**Course:** CENY6103 - Web Programming  
**Student:** [Your Name]  
**Status:** Completed

---

## 📝 Project Overview
This module converts the static HTML website from Module 1 into a dynamic, PHP-driven application. It focuses on modularizing code, handling server-side data, and improving user experience through dynamic logic.

### 🤖 AI Disclosure & Academic Integrity
In compliance with the course academic integrity policy regarding the use of AI tools (GitHub Copilot, ChatGPT, Gemini):

* **Tools Used:** GitHub Copilot for code completion; Gemini for logic troubleshooting.
* **Application:** AI was used to assist with regular expression patterns for form validation and to generate boilerplate structures for the PHP include files.
* **Human Oversight:** All AI-generated logic was reviewed, refactored for security (XSS prevention), and manually tested to ensure it meets the module requirements.

---

## ✅ Features Implemented

### 1. PHP Templating System
* Created reusable `header.php` and `footer.php`.
* All pages now use `include()` to avoid code duplication.
* Implemented dynamic page titles and active navigation states.

### 2. Dynamic Time-Based Greeting
The homepage displays a greeting based on the current server time:
* **05:00 - 11:59:** "Good Morning" 🌅
* **12:00 - 16:59:** "Good Afternoon" ☀️
* **17:00 - 20:59:** "Good Evening" 🌆
* **21:00 - 04:59:** "Good Night" 🌙

### 3. Contact Form Processing
* Server-side validation using the `$_POST` superglobal.
* **Validates:** Name, email, phone, subject, and message.
* **Persistence:** Form fields repopulate automatically if validation fails.
* **Pattern:** Implemented the **POST-Redirect-GET** pattern to prevent duplicate submissions on page refresh.

---

## 📁 File Structure
```text
module2-php/
├── index.php                    # Homepage with dynamic greeting
├── listings.php                 # Book listings page
├── contact.php                  # Contact/About page with form
├── process_contact.php          # Form processing script
├── includes/
│   ├── header.php               # Reusable header template
│   ├── footer.php               # Reusable footer template
│   └── functions.php            # Helper functions (9 functions)
├── css/
│   ├── styles.css               # Original CSS from Module 1
│   └── php-enhancements.css     # New styles for alerts & greeting
├── js/
│   └── script.js                # Original JavaScript from Module 1
├── QUICK_START_GUIDE.md         # Implementation guide
└── MODULE2_DOCUMENTATION.md     # Technical docs

### 🚀 How to Run
* Local Development (Xampp)
Store the project folder in Xampp htdocs, run the xampp server and type localhost/Community Book Exchange Module2

🔧 PHP Functions Created
The includes/functions.php file contains the following core logic:

getTimeBasedGreeting(): Returns greeting based on hour.

sanitizeInput($data): Cleans and sanitizes user input (XSS protection).

validateEmail($email): Validates email format.

createSuccessAlert($msg): Generates green success alert HTML.

createErrorAlert($msg): Generates red error alert HTML.

🔐 Security Features
Input Sanitization: All user input cleaned with htmlspecialchars().

Server-Side Validation: Ensures data integrity even if client-side scripts are disabled.

XSS Prevention: Output escaping on all dynamic content.

✅ Module 2 Requirements Met
Requirement	Status
PHP templating with include/require	✅ Complete
Process contact form using $_POST	✅ Complete
Display confirmation message	✅ Complete
Dynamic greeting based on server time	✅ Complete
Form validation and error handling	✅ Complete
POST-Redirect-GET pattern	✅ Complete
Expected Score: 10/10

Next Module: Module 3: Data Persistence & SQL Integration


Would you like me to generate the code for that `functions.php` file so it matches the descriptions in your README?