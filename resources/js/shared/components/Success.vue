<template>
  <div>
    <div class="text-center success">
    <i class="fas fa-thumbs-up"></i>
    <h1>Success ! </h1>
    <h2><slot></slot></h2>
  </div>
  </div>
</template>

<style scoped>
  .error {
    color: #999;
    font-size: 10rem;
  }
</style>