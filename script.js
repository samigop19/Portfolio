// Mobile menu toggle with enhanced animation
document.getElementById('mobile-menu-btn').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    const icon = this.querySelector('i');
    
    mobileMenu.classList.toggle('open');
    
    // Change hamburger icon
    if (mobileMenu.classList.contains('open')) {
        icon.className = 'fas fa-times text-white text-xl';
        document.body.style.overflow = 'hidden';
    } else {
        icon.className = 'fas fa-bars text-white text-xl';
        document.body.style.overflow = 'auto';
    }
});

// Close mobile menu
document.getElementById('close-mobile-menu').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const icon = mobileMenuBtn.querySelector('i');
    
    mobileMenu.classList.remove('open');
    icon.className = 'fas fa-bars text-white text-xl';
    document.body.style.overflow = 'auto';
});

// Close mobile menu when clicking on nav links
document.querySelectorAll('.mobile-nav-link').forEach(link => {
    link.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const icon = mobileMenuBtn.querySelector('i');
        
        mobileMenu.classList.remove('open');
        icon.className = 'fas fa-bars text-white text-xl';
        document.body.style.overflow = 'auto';
    });
});

// Enhanced form submission with client-side email functionality
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    const formData = new FormData(this);
    
    // Get form data
    const name = formData.get('name');
    const email = formData.get('email');
    const message = formData.get('message');
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner animate-spin mr-2"></i>Opening Email...';
    submitBtn.disabled = true;
    
    // Create mailto link
    const subject = encodeURIComponent(`Portfolio Contact from ${name}`);
    const body = encodeURIComponent(`Name: ${name}\nEmail: ${email}\n\nMessage:\n${message}`);
    const mailtoLink = `mailto:samuelbonadoignacio19@gmail.com?subject=${subject}&body=${body}`;
    
    // Open email client
    window.location.href = mailtoLink;
    
    // Show success message
    setTimeout(() => {
        submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Email Opened!';
        submitBtn.className = submitBtn.className.replace('btn-primary', 'bg-green-600 hover:bg-green-700');
        
        // Reset form after 3 seconds
        setTimeout(() => {
            this.reset();
            submitBtn.innerHTML = originalText;
            submitBtn.className = submitBtn.className.replace('bg-green-600 hover:bg-green-700', 'btn-primary');
            submitBtn.disabled = false;
        }, 3000);
    }, 500);
});

// Smooth scrolling for navigation links with offset for fixed nav
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        const headerOffset = 80;
        const elementPosition = target.offsetTop;
        const offsetPosition = elementPosition - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
        });
    });
});

// Add scroll-based navigation highlighting and color changing
window.addEventListener('scroll', function() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('nav a[href^="#"]');
    const portfolioText = document.querySelector('nav h1');
    const mobileMenuIcon = document.querySelector('#mobile-menu-btn i');
    
    let current = 'home'; // Default to home
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (scrollY >= sectionTop - 100) {
            current = section.getAttribute('id');
        }
    });

    // Change navigation colors based on current section
    if (current === 'home') {
        // On green home background - use white text
        navLinks.forEach(link => {
            link.classList.remove('text-gray-800', 'hover:text-green-600');
            link.classList.add('text-white', 'hover:text-green-300');
        });
        portfolioText.classList.remove('gradient-text');
        portfolioText.classList.add('text-white');
        mobileMenuIcon.classList.remove('text-gray-800');
        mobileMenuIcon.classList.add('text-white');
    } else {
        // On other sections - use dark text
        navLinks.forEach(link => {
            link.classList.remove('text-white', 'hover:text-green-300');
            link.classList.add('text-gray-800', 'hover:text-green-600');
        });
        portfolioText.classList.remove('text-white');
        portfolioText.classList.add('gradient-text');
        mobileMenuIcon.classList.remove('text-white');
        mobileMenuIcon.classList.add('text-gray-800');
    }

    // Handle active link highlighting
    navLinks.forEach(link => {
        // Remove all possible active classes first
        link.classList.remove('text-green-600', 'text-green-300', 'scale-105');
        
        // Add active styles only to the current section's link
        if (link.getAttribute('href').substring(1) === current) {
            if (current === 'home') {
                link.classList.add('text-green-300', 'scale-105');
            } else {
                link.classList.add('text-green-600', 'scale-105');
            }
        }
    });
});

// Add intersection observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe sections for scroll animations
document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('section');
    sections.forEach((section, index) => {
        if (index > 0) { // Skip hero section
            section.style.opacity = '0';
            section.style.transform = 'translateY(30px)';
            section.style.transition = 'all 0.6s ease-out';
            observer.observe(section);
        }
    });

    // Add typing effect to hero title
    const heroTitle = document.querySelector('#home h1');
    if (heroTitle) {
        const text = heroTitle.textContent;
        heroTitle.textContent = '';
        heroTitle.style.borderRight = '2px solid white';
        
        let i = 0;
        const typeWriter = () => {
            if (i < text.length) {
                heroTitle.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 100);
            } else {
                setTimeout(() => {
                    heroTitle.style.borderRight = 'none';
                }, 1000);
            }
        };
        
        setTimeout(typeWriter, 1000);
    }
});

// Add parallax effect to hero background
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector('.hero-bg');
    if (parallax) {
        const speed = scrolled * 0.5;
        parallax.style.transform = `translateY(${speed}px)`;
    }
});