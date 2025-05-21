@props(['type' => 'success', 'message' => ''])
<div
      x-data="{
          show: false,
          message: '',
          type: 'success',
          icon: '',
          get bgClass() {
              return {
                  success: 'bg-green-500',
                  error: 'bg-red-500',
                  warning: 'bg-yellow-500',
                  info: 'bg-blue-500'
              }[this.type] || 'bg-gray-700';
          },
          get iconSvg() {
              switch (this.type) {
                  case 'success':
                      return `<svg class='h-6 w-6 mr-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg>`;
                  case 'error':
                      return `<svg class='h-6 w-6 mr-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path></svg>`;
                  case 'warning':
                      return `<svg class='h-6 w-6 mr-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z'></path></svg>`;
                  case 'info':
                      return `<svg class='h-6 w-6 mr-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 16h-1v-4h-1m1-4h.01'></path></svg>`;
                  default:
                      return '';
              }
          }
      }"
      x-init="
          Livewire.on('notify', params => {
              type = params.type || 'success';
              message = params.message || '';
              show = true;
              setTimeout(() => show = false, params.duration || 3000);
          })
      "
      x-show="show"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 transform translate-y-2"
      x-transition:enter-end="opacity-100 transform translate-y-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 transform translate-y-0"
      x-transition:leave-end="opacity-0 transform translate-y-2"
      :class="`fixed bottom-0 right-0 m-6 p-4 text-white rounded-lg shadow-lg flex items-center ${bgClass}`"
      style="display: none;"
      role="alert"
  >
      <span x-html="iconSvg"></span>
      <span x-text="message"></span>
  </div>
