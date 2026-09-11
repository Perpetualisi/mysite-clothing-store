  <script>
    const revealEls = document.querySelectorAll('.product-card, .blog-card, .value');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
        }
      });
    }, { threshold: 0.15 });

    revealEls.forEach(el => {
      el.classList.add('reveal');
      observer.observe(el);
    });
  </script>
</body>
</html>