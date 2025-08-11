# Email Setup Instructions

The contact form currently saves messages to `contact_messages.txt` but doesn't send actual emails because local MAMP doesn't have SMTP configured.

## Option 1: Use Formspree (Recommended - Easy Setup)

1. Go to [https://formspree.io/](https://formspree.io/)
2. Sign up for a free account
3. Create a new form
4. Get your form endpoint (looks like: `https://formspree.io/f/YOUR_FORM_ID`)
5. Update the form in `index.html`:

```html
<form action="https://formspree.io/f/YOUR_FORM_ID" method="POST">
    <input type="hidden" name="_replyto" value="samuelbonadoignacio19@gmail.com">
    <input type="hidden" name="_subject" value="Portfolio Contact Form">
    <!-- rest of your form fields -->
</form>
```

6. Remove the JavaScript form handling or modify it to work with Formspree

## Option 2: Use EmailJS (JavaScript-based)

1. Go to [https://www.emailjs.com/](https://www.emailjs.com/)
2. Sign up and create a service
3. Create an email template
4. Update `script.js` to use EmailJS SDK
5. Add EmailJS SDK to your HTML:

```html
<script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
```

## Option 3: Deploy to a hosting service

Deploy your site to:
- Netlify (has built-in form handling)
- Vercel 
- Heroku
- Any hosting service with PHP and SMTP support

## Current Status

For now, all messages are saved to `contact_messages.txt` in your Portfolio folder. You can check this file to see submitted messages while you set up proper email delivery.