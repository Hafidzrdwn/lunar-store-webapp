<?php View::extend('client'); ?>

<?php View::startSection('title'); ?>
Premium Digital Products
<?php View::endSection(); ?>

<?php View::startSection('content'); ?>
<!-- About Section -->
<section class="relative pt-12 pb-24 mr-0 xl:mr-0 lg:mr-5">
  <div class="w-full px-4 mx-auto max-w-7xl md:px-5 lg:px-14">
    <div
      class="grid items-center justify-start w-full grid-cols-1 gap-10 xl:gap-10 lg:grid-cols-2">
      <div
        class="inline-flex flex-col items-center justify-center w-full gap-6 lg:items-start">
        <div class="flex flex-col items-start justify-center w-full gap-8">
          <div
            class="flex flex-col items-center justify-start gap-2 lg:items-start">
            <h6 class="text-base font-normal leading-relaxed text-blue-600">
              About Us
            </h6>
            <div
              class="flex flex-col items-center justify-start w-full gap-3 lg:items-start">
              <h2
                class="text-4xl font-bold leading-normal text-center text-blue-600 font-manrope lg:text-start">
                The Tale of Our Achievement Story
              </h2>
              <p
                class="text-base font-normal leading-relaxed text-center text-gray-600 lg:text-start">
                Our achievement story is a testament to teamwork and
                perseverance. Together, we've overcome challenges,
                celebrated victories, and created a narrative of progress
                and success.
              </p>
            </div>
          </div>
          <div
            class="flex flex-col items-start justify-center w-full gap-4">
            <div
              class="grid items-center justify-start w-full grid-cols-1 gap-4 md:grid-cols-2">
              <div
                class="w-full h-full p-3.5 rounded-xl border border-blue-200 hover:border-blue-500 transition-all duration-700 ease-in-out flex-col justify-start items-start gap-2.5 inline-flex">
                <h4
                  class="text-2xl font-bold leading-9 text-blue-600 font-manrope">
                  2+ Years
                </h4>
                <p
                  class="text-base font-normal leading-relaxed text-gray-500">
                  Experience in Delivering Premium Digital Products
                </p>
              </div>
              <div
                class="w-full h-full p-3.5 rounded-xl border border-blue-200 hover:border-blue-500 transition-all duration-700 ease-in-out flex-col justify-start items-start gap-2.5 inline-flex">
                <h4
                  class="text-2xl font-bold leading-9 text-blue-600 font-manrope">
                  25+ Premium Apps
                </h4>
                <p
                  class="text-base font-normal leading-relaxed text-gray-500">
                  Designed to Meet the Needs of Modern Businesses
                </p>
              </div>
            </div>
            <div
              class="grid items-center justify-start w-full h-full grid-cols-1 gap-4 md:grid-cols-2">
              <div
                class="w-full p-3.5 rounded-xl border border-blue-200 hover:border-blue-500 transition-all duration-700 ease-in-out flex-col justify-start items-start gap-2.5 inline-flex">
                <h4
                  class="text-2xl font-bold leading-9 text-blue-600 font-manrope">
                  40+ Good Reviews
                </h4>
                <p
                  class="text-base font-normal leading-relaxed text-gray-500">
                  Recognizing Our Commitment to Quality and Excellence
                </p>
              </div>
              <div
                class="w-full h-full p-3.5 rounded-xl border border-blue-200 hover:border-blue-500 transition-all duration-700 ease-in-out flex-col justify-start items-start gap-2.5 inline-flex">
                <h4
                  class="text-2xl font-bold leading-9 text-blue-600 font-manrope">
                  99% Happy Clients
                </h4>
                <p
                  class="text-base font-normal leading-relaxed text-gray-500">
                  Mirrors our Focus on Client Satisfaction.
                </p>
              </div>
            </div>
          </div>
        </div>
        <a
          href="#teams"
          class="px-4 py-3 text-white bg-blue-500 rounded-md hover:bg-blue-600">
          Meet Our Team <i class="ml-1 text-sm fas fa-angle-double-right"></i>
        </a>
      </div>
      <div class="flex items-start justify-center w-full lg:justify-start">
        <div
          class="sm:w-[564px] w-full sm:h-[646px] h-full sm:bg-blue-100 rounded-3xl sm:border border-gray-200 relative">
          <img
            class="object-cover w-full h-full sm:mt-5 sm:ml-5 rounded-3xl"
            src="https://pagedone.io/asset/uploads/1717742431.png"
            alt="about Us image" />
        </div>
      </div>
    </div>
  </div>
</section>

<?php

$teams = [
  [
    "name" => "Zakiyah Yasmin A.D.",
    "role" => "Founder / CEO",
    "image" => "kia.jpg",
  ],
  [
    "name" => "Hafidz Ridwan Cahya",
    "role" => "CTO / Back-End Web Developer",
    "image" => "hafidz.jpg",
  ],
  [
    "name" => "Fajar Ramadhandi Hidayat",
    "role" => "CMO / Front-End Web Developer",
    "image" => "dhandi.jpg",
  ],
  [
    "name" => "Arnoldy Mahesa Riadhino",
    "role" => "COO / Database Administrator",
    "image" => "dydy.jpg",
  ]
];

?>

<!-- Team Section -->
<section class="pb-24 pt-20" id="teams">
  <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-14">
    <div class="mb-12">
      <h2
        class="mb-6 text-5xl font-bold text-center text-blue-600 font-manrope">
        Meet the brains
      </h2>
      <p class="text-lg text-center text-gray-500">
        These people work on making our product best.
      </p>
    </div>
    <div
      class="flex flex-wrap justify-between max-w-3xl mx-auto gap-y-14 lg:max-w-full">
      <?php foreach ($teams as $team): ?>
        <div
          class="group block text-center lg:w-1/5 sm:w-1/3 min-[450px]:w-1/2 w-full">
          <div class="relative mb-5">
            <img
              src="<?= asset('client/images/' . $team['image']); ?>"
              alt="<?= $team['name']; ?> image"
              class="object-cover mx-auto duration-500 border-2 border-transparent border-solid w-28 h-28 rounded-2xl ransition-all group-hover:border-blue-500" />
          </div>
          <h4
            class="mb-2 text-xl font-semibold text-center text-gray-900 transition-all duration-500 group-hover:text-blue-500">
            <?= $team['name']; ?>
          </h4>
          <span
            class="block text-center text-gray-500 transition-all duration-500 group-hover:text-gray-900"><?= $team['role']; ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php View::endSection(); ?>