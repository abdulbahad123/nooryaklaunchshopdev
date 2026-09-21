Add scroll-triggered animations across the entire website for all devices and all themes.

Apply the animation consistently to all relevant sections, cards, images, text blocks, buttons, and content elements across the website.
Support desktop/laptop, tablet, and mobile screens.
Use subtle left-to-right slide-in and fade-in/fade-out animations.
The animation should trigger every time an element enters the viewport while scrolling, not just the first time the page loads.
When the user scrolls back up and an element enters the viewport again, the animation should play again.
Use smooth, lightweight animations that do not cause layout shifting, flickering, or horizontal overflow.
Elements should remain completely hidden from the animation area until they enter the viewport; do not allow animated content to appear below/outside the intended page boundaries.
Prevent any horizontal scrolling caused by the slide animation. Ensure overflow-x: hidden is handled safely at the appropriate page/container level.
Do not break existing responsive layouts, theme styles, Elementor/WooCommerce components, menus, popups, sliders, or other existing animations.
Make the animation work consistently regardless of the active website theme.
Respect prefers-reduced-motion by reducing/disabling animations for users who have requested reduced motion.
Optimize the implementation so it does not noticeably affect page speed or mobile performance.
Use an efficient IntersectionObserver-based implementation rather than continuously running scroll handlers.
Avoid animating elements that are not visible or that are outside the viewport unnecessarily.
Test the implementation on mobile, tablet, laptop, and large desktop screen sizes.

counter animation for all pages 

Important: The animation must replay whenever the element leaves and re-enters the viewport during scrolling. It should not be a one-time animation only. Do not create horizontal overflow or cause content to appear outside the page while the animation is running.