import { onMounted, onBeforeUnmount } from "vue";

export function useClickOutside(elementRef, callback) {
  
  const listener = (e) => {
    if (e.target == elementRef.value || e.composedPath().includes(elementRef.value)) {
      return;
    }
    if (typeof callback === 'function') {
      callback();
    }
  };

  onMounted(() => {
    window.addEventListener("click", listener);
  });

  onBeforeUnmount(() => {
    window.removeEventListener("click", listener);
  });
}