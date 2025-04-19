<?php View::extend('client'); ?>
<?php View::startSection('title'); ?>
Premium Digital Products
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<!-- Hero Section -->
<section
  class="relative py-20 text-white overflow-x-clip bg-gradient-to-r from-blue-500 to-blue-800">
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute left-0 right-0 top-0 bg-white/10 h-[1px]"></div>
    <div class="absolute left-0 right-0 bottom-0 bg-white/5 h-[1px]"></div>
    <div
      class="absolute rounded-full -left-40 -top-40 h-80 w-80 bg-white/10 blur-3xl"></div>
    <div
      class="absolute rounded-full -right-40 -bottom-40 h-80 w-80 bg-white/10 blur-3xl"></div>
  </div>
  <div class="container relative px-4 mx-auto max-w-7xl sm:px-6 lg:px-14">
    <div class="lg:flex lg:items-center lg:justify-between">
      <div class="lg:w-1/2">
        <h1 class="mb-8 text-4xl font-bold md:text-5xl lg:text-6xl">
          Premium Apps for Work, Lifestyle & Beyond
        </h1>
        <p class="mb-6 text-xl text-white">
          Boost productivity, enhance your lifestyle, and simplify daily
          tasks with top-tier digital solutions.
        </p>
        <div class="flex flex-col gap-4 sm:flex-row">
          <a href="#features"
            class="px-6 py-3 text-lg font-medium text-white transition-all border border-white rounded-md active:scale-[0.9] hover:bg-white/10">
            Explore More
          </a>
          <a href="<?= site_url('/catalog'); ?>"
            class="px-6 py-3 text-lg font-medium text-blue-500 transition-all bg-white rounded-md active:scale-[0.9] hover:bg-blue-50">
            Order Now <i class="ml-1 text-sm fas fa-angle-double-right"></i>
          </a>
        </div>
      </div>
      <div class="mt-10 lg:mt-0 lg:w-1/2">
        <div class="relative h-64 sm:h-72 md:h-80 lg:h-96">
          <!--This is moon animation-->
          <div class="transform -translate-x-40 -translate-y-80">
            <dotlottie-player src="https://lottie.host/07e69a38-119f-4261-a8e5-326f33bb2158/RY7A2B3V50.lottie" background="transparent" speed="1" style="width: 1150px; height: 1150px" loop autoplay></dotlottie-player>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Trusted By Section -->
<section class="py-14 bg-[#e4e2dd]/30">
  <div class="container px-4 mx-auto sm:px-6 lg:px-14">
    <p class="text-xl text-center text-gray-600 mb-14">
      Trusted to fulfil your digital app needs
    </p>
    <div
      class="grid items-center grid-cols-2 gap-8 md:grid-cols-3 lg:grid-cols-6 justify-items-center">
      <div
        class="flex items-center justify-center h-12 transition-all grayscale opacity-70 hover:grayscale-0 hover:opacity-100">
        <img
          src="<?= public_path('assets/client/images/netf.png'); ?>"
          alt="Netflix Logo"
          width="80"
          height="48" />
      </div>
      <div
        class="flex items-center justify-center h-12 transition-all grayscale opacity-70 hover:grayscale-0 hover:opacity-100">
        <img
          src="<?= public_path('assets/client/images/youtube.svg'); ?>"
          alt="YouTube Premium Icon"
          width="80"
          height="48" />
      </div>
      <div
        class="flex items-center justify-center h-12 transition-all grayscale opacity-70 hover:grayscale-0 hover:opacity-100">
        <img
          src="<?= public_path('assets/client/images/spotify.svg'); ?>"
          alt="Spotify Icon"
          width="80"
          height="48" />
      </div>
      <div
        class="flex items-center justify-center h-12 transition-all grayscale opacity-70 hover:grayscale-0 hover:opacity-100">
        <img
          src="<?= public_path('assets/client/images/canva.svg'); ?>"
          alt="Canva Icon"
          width="80"
          height="48" />
      </div>
      <div
        class="flex items-center justify-center h-12 transition-all grayscale opacity-70 hover:grayscale-0 hover:opacity-100">
        <img
          src="<?= public_path('assets/client/images/ml.png'); ?>"
          alt="Top Up Diamond Mobile Legends Icon"
          width="80"
          height="48" />
      </div>
      <div
        class="flex items-center justify-center h-12 transition-all grayscale opacity-70 hover:grayscale-0 hover:opacity-100">
        <img
          src="<?= public_path('assets/client/images/gpt.png'); ?>"
          alt="ChatGPT Icon"
          width="80"
          height="48" />
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20">
  <div class="container px-4 mx-auto sm:px-6 lg:px-14">
    <div class="max-w-3xl mx-auto mb-16 text-center">
      <h2 class="mb-4 text-3xl font-bold text-blue-600 md:text-4xl">
        Unlock the Full Potential of Your Digital Experience
      </h2>
      <p class="text-xl text-gray-500">
        Get premium digital products with top-tier features designed to
        elevate your business and gaming needs effortlessly. 🚀
      </p>
    </div>

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
      <div
        class="p-8 transition-all duration-300 transform bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-xl hover:scale-105 hover:border-blue-300 hover:bg-blue-50/30 group">
        <div
          class="mb-4 text-blue-500 transition-transform duration-300 group-hover:text-blue-600">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-8 h-8"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
        </div>
        <h3
          class="mb-3 text-xl font-semibold transition-colors duration-300 group-hover:text-blue-600">
          Quick Service
        </h3>
        <p
          class="text-gray-600 transition-colors duration-300 group-hover:text-gray-800">
          Fast, responsive, and hassle-free—we ensure every client request
          is met with top-tier service in no time!
        </p>
      </div>

      <div
        class="p-8 transition-all duration-300 transform bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-xl hover:scale-105 hover:border-blue-300 hover:bg-blue-50/30 group">
        <div
          class="mb-4 text-blue-500 transition-transform duration-300 group-hover:text-blue-600">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-8 h-8"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3
          class="mb-3 text-xl font-semibold transition-colors duration-300 group-hover:text-blue-600">
          Global Access
        </h3>
        <p
          class="text-gray-600 transition-colors duration-300 group-hover:text-gray-800">
          Seamlessly purchase and access digital products from anywhere in
          the world—no restrictions, no hassle.
        </p>
      </div>

      <div
        class="p-8 transition-all duration-300 transform bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-xl hover:scale-105 hover:border-blue-300 hover:bg-blue-50/30 group">
        <div
          class="mb-4 text-blue-500 transition-transform duration-300 group-hover:text-blue-600">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-8 h-8"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
          </svg>
        </div>
        <h3
          class="mb-3 text-xl font-semibold transition-colors duration-300 group-hover:text-blue-600">
          Scalable & Flexible
        </h3>
        <p
          class="text-gray-600 transition-colors duration-300 group-hover:text-gray-800">
          Whether you're an individual or a business, our platform grows
          with you, offering the best digital solutions without limits.
        </p>
      </div>

      <div
        class="p-8 transition-all duration-300 transform bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-xl hover:scale-105 hover:border-blue-300 hover:bg-blue-50/30 group">
        <div
          class="mb-4 text-blue-500 transition-transform duration-300 group-hover:text-blue-600">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-8 h-8"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
          </svg>
        </div>
        <h3
          class="mb-3 text-xl font-semibold transition-colors duration-300 group-hover:text-blue-600">
          Exclusive & Innovative
        </h3>
        <p
          class="text-gray-600 transition-colors duration-300 group-hover:text-gray-800">
          Get the latest premium apps, top-up services, and digital tools at
          the best prices—only at Lunar Store!
        </p>
      </div>

      <div
        class="p-8 transition-all duration-300 transform bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-xl hover:scale-105 hover:border-blue-300 hover:bg-blue-50/30 group">
        <div
          class="mb-4 text-blue-500 transition-transform duration-300 group-hover:text-blue-600">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-8 h-8"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          </svg>
        </div>
        <h3
          class="mb-3 text-xl font-semibold transition-colors duration-300 group-hover:text-blue-600">
          24/7 Priority Support
        </h3>
        <p
          class="text-gray-600 transition-colors duration-300 group-hover:text-gray-800">
          Our expert team is available around the clock to assist you with
          any questions or issues.
        </p>
      </div>

      <div
        class="p-8 transition-all duration-300 transform bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-xl hover:scale-105 hover:border-blue-300 hover:bg-blue-50/30 group">
        <div
          class="mb-4 text-blue-500 transition-transform duration-300 group-hover:text-blue-600">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-8 h-8"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3
          class="mb-3 text-xl font-semibold transition-colors duration-300 group-hover:text-blue-600">
          Trusted & Secure
        </h3>
        <p
          class="text-gray-600 transition-colors duration-300 group-hover:text-gray-800">
          Shop with confidence—our platform guarantees 100% secure
          transactions and verified digital products.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Products Section -->
<section id="products" class="py-20 bg-gradient-to-b from-white to-blue-50">
  <div class="container px-4 mx-auto sm:px-6 lg:px-14">
    <div class="max-w-3xl mx-auto mb-16 text-center">
      <h2 class="mb-4 text-3xl font-bold text-blue-600 md:text-4xl">
        Our Premium Digital Products
      </h2>
      <p class="text-xl text-gray-500">
        Explore our range of high-quality digital solutions designed to help
        your business thrive.
      </p>
    </div>

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
      <div
        class="overflow-hidden transition-shadow bg-white rounded-lg shadow-md hover:shadow-lg">
        <div class="relative h-64 bg-blue-100">
          <img
            src="<?= public_path('assets/client/images/premium_apps.png'); ?>"
            alt="Premium Apps"
            class="object-cover w-full h-full" />
        </div>
        <div class="p-6">
          <h3 class="mb-3 text-xl font-semibold">Premium Apps</h3>
          <p class="mb-4 text-gray-600">
            Unlock exclusive features, enjoy an ad-free experience, and get
            priority support for the ultimate performance!
          </p>
          <button
            class="inline-flex items-center px-4 py-2 text-blue-500 transition-all border border-blue-500 rounded-md hover:bg-blue-600 hover:text-white">
            Learn More
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-4 h-4 ml-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </button>
        </div>
      </div>

      <div
        class="overflow-hidden transition-shadow bg-white rounded-lg shadow-md hover:shadow-lg">
        <div class="relative h-64 bg-blue-100">
          <img
            src="<?= public_path('assets/client/images/topup_ml.png'); ?>"
            alt="Topup Mobile Legends"
            class="object-cover w-full h-full" />
        </div>
        <div class="p-6">
          <h3 class="mb-3 text-xl font-semibold">Top Up Mobile Legends</h3>
          <p class="mb-4 text-gray-600">
            Instantly recharge your diamonds and enjoy seamless gameplay!
            Secure, fast, and hassle-free transactions to keep you ahead in
            every battle!
          </p>
          <button
            class="inline-flex items-center px-4 py-2 text-blue-500 transition-all border border-blue-500 rounded-md hover:bg-blue-600 hover:text-white">
            Learn More
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-4 h-4 ml-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </button>
        </div>
      </div>

      <div
        class="overflow-hidden transition-shadow bg-white rounded-lg shadow-md hover:shadow-lg">
        <div class="relative h-64 bg-blue-1004">
          <img
            src="<?= public_path('assets/client/images/edu_features.png'); ?>"
            alt="Education Features"
            class="object-cover w-full h-full" />
        </div>
        <div class="p-6">
          <h3 class="mb-3 text-xl font-semibold">
            Education Premium Features
          </h3>
          <p class="mb-4 text-gray-600">
            Unlock advanced learning tools, exclusive content, and an
            ad-free experience for a smarter, more engaging education
            journey!
          </p>
          <button
            class="inline-flex items-center px-4 py-2 text-blue-500 transition-all border border-blue-500 rounded-md hover:bg-blue-600 hover:text-white">
            Learn More
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-4 h-4 ml-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div class="mt-12 text-center">
      <a href="<?= site_url('/catalog'); ?>"
        class="px-6 py-3 text-lg font-medium text-white transition-all bg-blue-500 rounded-md hover:bg-blue-600 active:scale-[0.9]">
        View All Products <i class="ml-1 text-sm fas fa-angle-double-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="py-20 bg-white">
  <div class="container px-4 mx-auto sm:px-6 lg:px-14">
    <div class="max-w-3xl mx-auto mb-16 text-center">
      <h2 class="mb-4 text-3xl font-bold text-blue-600 md:text-4xl">
        What Our Clients Say
      </h2>
      <p class="text-xl text-gray-500">
        Don't just take our word for it. Here's what our satisfied customers
        have to say.
      </p>
    </div>

    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
      <div class="p-8 border border-blue-100 rounded-lg bg-blue-50">
        <div class="flex items-center mb-6">
          <div class="mr-4">
            <div class="w-12 h-12 overflow-hidden rounded-full">
              <img
                src="<?= public_path('assets/client/images/adit.jpg'); ?>"
                alt="Aditya Heru"
                class="object-cover" />
            </div>
          </div>
          <div>
            <h4 class="font-semibold">Aditya Heru</h4>
            <p class="text-sm text-gray-600">Mahasiswa</p>
          </div>
        </div>
        <p class="italic text-gray-700">
          "10000/100 ramah di kantong, admin fast-responn!! prosesnya cepet
          pulaa❤️"
        </p>
      </div>

      <div class="p-8 border border-blue-100 rounded-lg bg-blue-50">
        <div class="flex items-center mb-6">
          <div class="mr-4">
            <div class="w-12 h-12 overflow-hidden rounded-full">
              <img
                src="<?= public_path('assets/client/images/suci.jpg'); ?>"
                alt="Michael Chen"
                class="object-cover" />
            </div>
          </div>
          <div>
            <h4 class="font-semibold">Sucianingsih</h4>
            <p class="text-sm text-gray-600">Mahasiswa</p>
          </div>
        </div>
        <p class="italic text-gray-700">
          "1000/10 sangat fast respon, cepet di proses, amanah, dan tentunya
          murah👍👍"
        </p>
      </div>

      <div class="p-8 border border-blue-100 rounded-lg bg-blue-50">
        <div class="flex items-center mb-6">
          <div class="mr-4">
            <div class="w-12 h-12 overflow-hidden rounded-full">
              <img
                src="<?= public_path('assets/client/images/aliando.jpg'); ?>"
                alt="Jessica Williams"
                class="object-cover" />
            </div>
          </div>
          <div>
            <h4 class="font-semibold">Aliando</h4>
            <p class="text-sm text-gray-600">Mahasiswa</p>
          </div>
        </div>
        <p class="italic text-gray-700">
          "10000/100 belinya gampang dan cepat, pelayanan responsif dan
          ramah. adminnya satset😍"
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="py-20 bg-blue-50">
  <div class="container px-4 mx-auto sm:px-6 lg:px-14">
    <div class="max-w-3xl mx-auto mb-16 text-center">
      <h2 class="mb-4 text-3xl font-bold text-blue-600 md:text-4xl">
        Simple, Transparent Pricing
      </h2>
      <p class="text-xl text-gray-500">
        Choose the plan that works best for your needs.
      </p>
    </div>

    <div class="grid gap-8 md:grid-cols-3">
      <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <div class="p-8">
          <h3 class="mb-2 text-2xl font-bold">Spotify</h3>
          <div class="mb-4">
            <span class="text-4xl font-bold">34.000 IDR</span>
            <span class="text-gray-600"> per month</span>
          </div>
          <p class="mb-6 text-gray-600">
            For streaming your favorite songs ad-free.
          </p>
          <ul class="mb-8 space-y-3">
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Listen Together with Friends in Real Time</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Exclusive Podcasts & Content</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Lyrics Display for Karaoke Mode</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Ad-Free Music Streaming</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Download Songs for Offline Listening</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Unlimited Skips</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>High-Quality Audio (Up to 320 kbps)</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Listen on Any Device</span>
            </li>
          </ul>
          <button
            class="w-full px-4 py-3 text-white bg-gray-800 rounded-md hover:bg-gray-900">
            Get Started
          </button>
        </div>
      </div>

      <div
        class="overflow-hidden scale-105 bg-white rounded-lg shadow-md ring-2 ring-blue-500">
        <div
          class="py-4 font-bold text-center text-white bg-blue-500 text-md">
          Most Popular
        </div>
        <div class="p-8">
          <h3 class="mb-2 text-2xl font-bold">Netflix</h3>
          <div class="mb-4">
            <span class="text-4xl font-bold">41.500 IDR</span>
            <span class="text-gray-600"> per month</span>
          </div>
          <p class="mb-6 text-gray-600">
            The ideal comfort for watching a movie.
          </p>
          <ul class="mb-8 space-y-3">
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>4K + HDR Quality</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>No Ads</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Download for Offline Viewing</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Access on All Devices</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Exclusive Movies & Series</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Dolby Atmos & Surround Sound</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Multiple Language Subtitles & Dubs</span>
            </li>
          </ul>
          <button
            class="inline-flex justify-center items-center w-full px-4 py-3 text-white bg-blue-500 rounded-md hover:bg-blue-600">
            Get Started
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-4 h-4 ml-2"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </button>
        </div>
      </div>

      <div class="overflow-hidden bg-white rounded-lg shadow-md">
        <div class="p-8">
          <h3 class="mb-2 text-2xl font-bold">Canva</h3>
          <div class="mb-4">
            <span class="text-4xl font-bold">8.000 IDR</span>
            <span class="text-gray-600"> per month</span>
          </div>
          <p class="mb-6 text-gray-600">
            For an optimal digital editing experience.
          </p>
          <ul class="mb-8 space-y-3">
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Access to All Premium Templates</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Free Images, Videos, and Elements</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Background Remover</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Resize Designs with One Click</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Custom Brand Kit</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Real-Time Team Collaboration</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Download in High Quality</span>
            </li>
            <li class="flex items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 mr-2 text-blue-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Premium Animations & Effects</span>
            </li>
          </ul>
          <button
            class="w-full px-4 py-3 text-white bg-gray-800 rounded-md hover:bg-gray-900">
            Get Started
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-20 text-white bg-blue-600">
  <div class="container px-4 mx-auto sm:px-6 lg:px-14">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="mb-6 text-3xl font-bold md:text-4xl">
        Ready to Upgrade Your Digital Experience?
      </h2>
      <p class="mb-8 text-xl text-blue-100">
        Join thousands of users enjoying seamless access to premium apps,
        game top-ups, and exclusive digital services—only at Lunar Store! 🚀
      </p>
      <div class="flex flex-col justify-center gap-4 sm:flex-row">
        <a
          href="<?= site_url('/login'); ?>"
          class="inline-flex items-center px-6 py-3 text-lg font-medium text-blue-600 transition-all bg-white rounded-md hover:bg-blue-50 active:scale-[0.9]">
          Login and Order Now <i class="ml-2 fas fa-sign-in-alt"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section id="contact" class="pt-10 pb-20 bg-white">
  <div class="container px-4 mx-auto sm:px-6 lg:px-14">
    <h2 class="mb-2 text-3xl font-bold text-blue-600">Get in Touch</h2>
    <p class="mb-8 text-lg text-blue-500">
      Have questions about our products or services? Our team is here to
      help.
    </p>

    <div class="flex items-center justify-between">
      <div class="flex items-center">
        <div class="p-4 mt-1 mr-4 text-blue-600 bg-blue-100 rounded-full">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-10 h-10"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-bold text-blue-500">Phone</h2>
          <p class="text-blue-500">+62 882-5766-6420</p>
        </div>
      </div>

      <div class="flex items-center">
        <div class="p-4 mt-1 mr-4 text-blue-600 bg-blue-100 rounded-full">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-10 h-10"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-bold text-blue-500">Email</h2>
          <p class="text-blue-500">lunary202@gmail.com</p>
        </div>
      </div>

      <div class="flex items-center">
        <div class="p-4 mt-1 mr-4 text-blue-600 bg-blue-100 rounded-full">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-10 h-10"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-bold text-blue-500">Address</h2>
          <p class="text-blue-500">
            Panglima Sudirman Street No. 101, Tuban, Indonesia
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<?php View::endSection(); ?>

<?php View::startSection('custom_js'); ?>
<script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
<?php View::endSection(); ?>