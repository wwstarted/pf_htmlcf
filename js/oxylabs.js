
    // Cuộn mượt khi click
    document.querySelectorAll('.review-sidebar a[href^="#"]').forEach(link => {
       link.addEventListener('click', function(e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
             window.scrollTo({
                top: target.offsetTop - 100, // trừ đi chiều cao header cố định nếu có
                behavior: 'smooth'
             });
          }
       });
    });

    // Scroll spy: tự highlight khi cuộn
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.review-sidebar a');

    window.addEventListener('scroll', () => {
       let current = '';
       sections.forEach(section => {
          const sectionTop = section.offsetTop - 120;
          const sectionHeight = section.offsetHeight;
          if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
             current = section.getAttribute('id');
          }
       });

       navLinks.forEach(link => {
          link.classList.remove('active-reviews');
          if (link.getAttribute('href') === `#${current}`) {
             link.classList.add('active-reviews');
          }
       });
    });

    
