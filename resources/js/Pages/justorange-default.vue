<script setup>
const features = [
  {
    title: "Country Filtering",
    desc: "Redirect based on user country with granular control."
  },
  {
    title: "Platform Filtering",
    desc: "Supports mobile, desktop, and Facebook browser targeting."
  },
  {
    title: "Referer Filtering",
    desc: "Cloak based on traffic referer for enhanced security."
  },
  {
    title: "Random Target URLs",
    desc: "Randomize landing URLs for each user visit."
  },
  {
    title: "Redirect or Template Rendering",
    desc: "Choose between redirect or cloaked template rendering method."
  },
  {
    title: "Customizable Slug",
    desc: "Create SEO-friendly slugs for each campaign."
  },
  {
    title: "Multi-Domain Support",
    desc: "Manage and rotate across multiple domains."
  },
  {
    title: "Multi-User Access",
    desc: "Collaborate with teams through individual user access."
  },
  {
    title: "Real-Time Statistics",
    desc: "Monitor clicks, devices, countries, and referers live."
  },
]
</script>

<template>
  <div class="min-h-screen bg-gray-50 text-gray-800">
    <!-- Hero Section -->
    <section class="bg-white shadow-md">
      <div class="max-w-7xl mx-auto px-6 py-20 text-center">
        <h1 class="text-4xl font-bold mb-4">Cloaking Manager</h1>
        <p class="text-lg text-gray-600 mb-6">
          Powerful cloaking management tool with advanced filters and multi-user support.
        </p>
        <a  href="/dashboard" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl text-lg">
          Get Started
        </a>
      </div>
    </section>

    <!-- Features Section -->
    <section class="max-w-7xl mx-auto px-6 py-16">
      <h2 class="text-3xl font-semibold text-center mb-12">Key Features</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div
          v-for="(feature, index) in features"
          :key="index"
          class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition"
        >
          <h3 class="text-xl font-semibold mb-2">{{ feature.title }}</h3>
          <p class="text-gray-600">{{ feature.desc }}</p>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-100 py-6 mt-12 text-center text-sm text-gray-500">
      &copy; 2025 Cloaking Manager. All rights reserved.
    </footer>
  </div>
</template>