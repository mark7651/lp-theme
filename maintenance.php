<?php
/**
 * The template for maintenance page
 *
 */
?>

<style>
  html {
  font-size: 20px;
}

*,
::after,
::before {
  -webkit-font-smoothing: antialiased;
  -moz-font-smoothing: antialiased;
  -o-font-smoothing: antialiased;
  text-rendering: optimizeLegibility;
  -moz-osx-font-smoothing: grayscale;
  -webkit-text-size-adjust: 100%;
  -moz-text-size-adjust: 100%;
  text-size-adjust: 100%;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-touch-callout: none;
  -webkit-tap-highlight-color: transparent;
  -webkit-overflow-scrolling: touch;
  outline: none
}
body {
  margin: 0 auto;
  font-size: 1rem;
  font-weight: 400;
  font-family: 'Arial', sans-serif;
  line-height: 1.5;
  color: #16171D;
  background-color: #ffffff;
}


h6,
h5,
h4,
h3,
h2,
h1 {
  margin-top: 0;
  margin-bottom: 1rem;
  font-weight: 700;
}
h1{
  font-size: calc(30px + 82*(100vw - 280px)/1640);
}
p {
  font-size: 1.4rem;
  margin-top: 0;
  margin-bottom: 1rem;
}
.container {
  position: relative;
  width: 100%;
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto;
}

@media (min-width: 576px) {
  .container {
    max-width: 540px;
  }
}

@media (min-width: 768px) {
  .container {
    max-width: 720px;
  }
}

@media (min-width: 992px) {
  .container {
    max-width: 960px;
  }
}

@media (min-width: 1200px) {
  .container {
    max-width: 1140px;
  }
}

@media (min-width: 1400px) {
  .container {
    max-width: 1290px;
  }
}
.maintenance-mode {
    display: grid;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    text-align: center;
}
</style>

<main class="maintenance-mode" role="main">
  <div class="container">
    <div class="page-title__wrap section-title">
      <h1><?php _e('Under Maintenance', 'lptheme') ;?></h1>
    </div>

      <div class="entry-content">
        <p><?php _e('Website under planned maintenance. Please check back later.', 'lptheme');?></p>
      </div>

  </div>
</main>
