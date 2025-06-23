<template>
  <!-- Only show loader when isLoading is true -->
  <Teleport to="body" v-if="isLoading">
    <div class="fixed inset-0 bg-white/10 backdrop-blur-sm z-10 flex items-center justify-center">
      <div class="flex flex-col items-center space-y-4">
        <!-- Main Spinner -->
        <div class="relative">
          <div class="w-16 h-16 border-4 border-gray-200 rounded-full animate-spin border-t-gray-600"></div>
          <div class="absolute top-2 left-2 w-12 h-12 border-4 border-transparent rounded-full animate-pulse border-t-gray-400"></div>
        </div>

        <!-- Loading Text -->
        <div class="text-center">
          <h3 class="text-lg font-semibold text-gray-700 mb-1">{{ loadingText }}</h3>
          <p class="text-sm text-gray-500">{{ subText }}</p>
        </div>

        <!-- Animated Dots -->
        <div class="flex space-x-1">
          <div
            v-for="i in 3"
            :key="i"
            class="w-2 h-2 bg-gray-600 rounded-full animate-bounce"
            :style="{ animationDelay: `${(i - 1) * 0.1}s` }"
          ></div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script>
export default {
  name: 'LoaderPage',
  props: {
    isLoading: {
      type: Boolean,
      default: false
    },
    loadingText: {
      type: String,
      default: 'Loading...'
    },
    subText: {
      type: String,
      default: 'Please wait while we process your request'
    }
  }
}
</script>

<style scoped>
/* Custom animation for bouncing dots */
@keyframes bounce {
  0%, 80%, 100% {
    transform: scale(0);
  }
  40% {
    transform: scale(1);
  }
}

.animate-bounce {
  animation: bounce 1.4s infinite ease-in-out both;
}

/* Ensure spinner animation works */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
