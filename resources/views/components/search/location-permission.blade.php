@props(['permission' => 'unknown', 'loading' => false, 'error' => ''])

<div class="location-permission-wrapper">
    @if($permission === 'unknown' || $loading)
        <!-- Permission Request Modal -->
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" 
             style="display: {{ $loading ? 'block' : 'none' }}">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="text-center">
                    @if($loading)
                        <div class="flex items-center justify-center mb-4">
                            <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Đang xác định vị trí...</h3>
                        <p class="text-gray-600 mb-4">Vui lòng chờ trong khi chúng tôi xác định vị trí của bạn.</p>
                    @else
                        <div class="mb-4">
                            <svg class="mx-auto h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Cho phép truy cập vị trí</h3>
                        <p class="text-gray-600 mb-4">
                            Chúng tôi cần quyền truy cập vị trí của bạn để hiển thị khoảng cách đến các địa điểm gần nhất và cung cấp kết quả tìm kiếm tốt hơn.
                        </p>
                        <div class="space-y-2">
                            <button wire:click="requestLocation" 
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                Cho phép truy cập vị trí
                            </button>
                            <button onclick="document.querySelector('.location-permission-wrapper').style.display='none'; localStorage.setItem('location-auto-request', 'false');"
                                    class="w-full bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                                Bỏ qua
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($permission === 'denied')
        <!-- Permission Denied Banner -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-yellow-800">Quyền truy cập vị trí bị từ chối</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>{{ $error ?: 'Bạn đã từ chối quyền truy cập vị trí. Chúng tôi không thể hiển thị khoảng cách đến các địa điểm.' }}</p>
                    </div>
                    <div class="mt-3">
                        <button wire:click="retryLocation"
                                class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-md text-sm hover:bg-yellow-200 transition-colors">
                            Thử lại
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($permission === 'error')
        <!-- Error Banner -->
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-red-800">Lỗi xác định vị trí</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>{{ $error }}</p>
                    </div>
                    <div class="mt-3">
                        <button wire:click="retryLocation"
                                class="bg-red-100 text-red-800 px-3 py-1 rounded-md text-sm hover:bg-red-200 transition-colors">
                            Thử lại
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($permission === 'granted')
        <!-- Success Banner -->
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Đã xác định vị trí thành công</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Chúng tôi sẽ hiển thị khoảng cách từ vị trí của bạn đến các địa điểm.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.location-permission-wrapper .fixed {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>