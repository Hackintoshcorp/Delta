@extends('app')

@section('title', 'Index')

@section('content')
<div class="min-h-full pb-20">
  <div class="relative">
    <div class="relative mx-auto max-w-2xl py-24 px-6 lg:max-w-7xl lg:py-32 lg:px-8">
      <div class="relative">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-none lg:text-6xl">Project Delta</h1>
        <p class="mt-6 max-w-2xl text-xl text-gray-500">Welcome back {{ auth()->user()->name }}, please choose one of four options listed below.</p>
        <div class="flex gap-x-[10px]">
          <form method="POST" action="{{ route('logout') }}" class="w-[200px]">
            @csrf
            <button type="submit" class="bg-rose-500 text-white hover:bg-rose-600 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Logout</button>
          </form>

          <select name="device" aria-label="Select device" class="w-[200px] bg-rose-500 text-white hover:bg-rose-600 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">
            @foreach ($devicesList as $device)
              <option value="{{ $device }}">{{ $device }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>
  <main>
    <section class="relative" aria-labelledby="pricing-heading">

      <div class="mx-auto space-y-12 px-6 lg:gap-y-4 lg:grid lg:max-w-7xl lg:grid-cols-4 lg:gap-x-4 lg:space-y-0 lg:px-8">
        <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
          <div class="flex-1">
            <h3 class="text-xl font-semibold text-gray-900">Optimization</h3>
            <p id="lastoptimize" class="absolute top-0 -translate-y-1/2 transform rounded-full bg-rose-500 py-1.5 px-4 text-sm font-semibold text-white">Performed: {{ $lastDates['optimize'] ?? 'Not available' }}</p>
            <p class="mt-6 text-gray-500">When you click this you can optimize your computer and make it as fast as it was, if not faster.</p>
          </div>
          <button data-command="[optimize]" data-execute="optimize" data-date="{{ now()->toDateString() }}" class="history postdata bg-rose-50 text-rose-700 hover:bg-rose-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Begin</button>
        </div>
        <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
          <div class="flex-1">
            <h3 class="text-xl font-semibold text-gray-900">One file scan</h3>
            <p id="lastscanOne" class="absolute top-0 -translate-y-1/2 transform rounded-full bg-rose-500 py-1.5 px-4 text-sm font-semibold text-white">Performed: {{ $lastDates['scanOne'] ?? 'Not available' }}</p>
            <p class="mt-6 text-gray-500">When you click this you can scan one file to check if it contains a virus inside itself.</p>
          </div>
          <button data-target="#scanOneFileModal" data-execute="scanOne" data-date="{{ now()->toDateString() }}" class="history open-modal bg-rose-500 text-white hover:bg-rose-600 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Begin</button>
        </div>
        <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
          <div class="flex-1">
            <h3 class="text-xl font-semibold text-gray-900">Scan entire PC</h3>
            <p id="lastscanFull" class="absolute top-0 -translate-y-1/2 transform rounded-full bg-rose-500 py-1.5 px-4 text-sm font-semibold text-white">Performed: {{ $lastDates['scanFull'] ?? 'Not available' }}</p>
            <p class="mt-6 text-gray-500">When you click this you can scan your entire computer to check if it contains viruses.</p>
          </div>
          <button data-command="[scan]" data-execute="scanFull" data-date="{{ now()->toDateString() }}" class="history postdata bg-rose-500 text-white hover:bg-rose-600 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Begin</button>
        </div>
        <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
          <div class="flex-1">
            <h3 class="text-xl font-semibold text-gray-900">Regenerate keys</h3>
            <p id="lastregenKeys" class="absolute top-0 -translate-y-1/2 transform rounded-full bg-rose-500 py-1.5 px-4 text-sm font-semibold text-white">Performed: {{ $lastDates['regenKeys'] ?? 'Not available' }}</p>
            <p class="mt-6 text-gray-500">When you click this you will regenerate keys that are used to check for viruses on your computer.</p>
          </div>
          <button data-command="[repair]" data-execute="regenKeys" data-date="{{ now()->toDateString() }}" class="history postdata bg-rose-50 text-rose-700 hover:bg-rose-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Begin</button>
        </div>
        <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
          <div class="flex-1">
            <h3 class="text-xl font-semibold text-gray-900">Devices</h3>
            <p class="mt-6 text-gray-500">When you click this you will see the list of devices added to your account that you can manage.</p>
          </div>
          <button data-target="#devicesModal" class="open-modal bg-rose-50 text-rose-700 hover:bg-rose-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Show</button>
        </div>
        <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
          <div class="flex-1">
            <h3 class="text-xl font-semibold text-gray-900">Settings</h3>
            <p class="mt-6 text-gray-500">When you click this you will be able to manage your account settings on this platform.</p>
          </div>
          <button data-target="#settingsModal" class="open-modal bg-rose-50 text-rose-700 hover:bg-rose-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Edit</button>
        </div>
        <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
          <div class="flex-1">
            <h3 class="text-xl font-semibold text-gray-900">History</h3>
            <p class="mt-6 text-gray-500">When you click this you will be able to see the list of events that have happened.</p>
          </div>
          <button data-target="#historyModal" class="open-modal bg-rose-50 text-rose-700 hover:bg-rose-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Show</button>
        </div>
        <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
          <div class="flex-1">
            <h3 class="text-xl font-semibold text-gray-900">Plan</h3>
            <p class="mt-6 text-gray-500">When you click this you will be able to manage the plan of this account.</p>
          </div>
          <button class="bg-rose-50 text-rose-700 hover:bg-rose-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Show</button>
        </div>
      </div>
    </section>
    <div class="relative mt-10">
      <div aria-hidden="true" class="absolute inset-x-0 h-1/2 "></div>
      <div class="relative mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
        <div class="relative overflow-hidden rounded-xl bg-rose-500 py-24 px-8 shadow-2xl lg:grid lg:grid-cols-2 lg:gap-x-8 lg:px-16">
          <div class="absolute inset-0 opacity-50 mix-blend-multiply saturate-0 filter">
            <img src="https://images.unsplash.com/photo-1601381718415-a05fb0a261f3?ixid=MXwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8ODl8fHxlbnwwfHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=1216&amp;q=80" alt="" class="h-full w-full object-cover" referrerpolicy="no-referrer">
          </div>
          <div class="relative lg:col-span-1">
            <blockquote class="mt-6 text-white">
              <p class="text-xl font-medium sm:text-2xl">This project is a simple demonstration on how anyone can build a fairly simple antivirus with device optimization capabilities.</p>
              <footer class="mt-6">
                <p class="flex flex-col font-medium">
                  <span>Laurynas Bielevičius</span>
                </p>
              </footer>
            </blockquote>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<div aria-live="assertive" class="notification hidden pointer-events-none fixed inset-0 flex flex-col items-end px-4 py-6 sm:items-start sm:p-6" style="flex-wrap:wrap;"></div>

{{-- ============================ ONE FILE SCAN MODAL ============================ --}}
<div id="scanOneFileModal" class="hidden relative z-10" aria-labelledby="scan-modal-title" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
  <div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
        <div>
          <div class="text-center">
            <h3 class="text-lg font-medium leading-6 text-gray-900" id="scan-modal-title">Drag a file to scan</h3>
            <div class="mt-2">
              <div class="sm:grid sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                <div class="mt-1 sm:col-span-2 sm:mt-0">
                  <div class="flex max-w-lg justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                    <div id="drop_zone" class="space-y-1 text-center">
                      <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                      </svg>
                      <div class="text-sm text-gray-600">
                        <p class="pl-1">Drag and drop</p>
                      </div>
                      <p class="text-xs text-gray-500">Everything will be done automatically</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-5 sm:mt-6">
          <button type="button" data-target="#scanOneFileModal" class="close-modal mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ============================ DEVICES MODAL ============================ --}}
<div class="hidden relative z-10" id="devicesModal" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
  <div class="fixed inset-0 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
      <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
        <div class="pointer-events-auto relative w-96">
          <div class="absolute top-0 left-0 -ml-8 flex pt-4 pr-2 sm:-ml-10 sm:pr-4">
            <button type="button" data-target="#devicesModal" class="close-modal rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
              <span class="sr-only">Close panel</span>
              <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <div class="h-full overflow-y-auto bg-white p-8">
            <div class="space-y-6 pb-16">
              <div>
                <h3 class="font-medium text-gray-900">Devices added to this account</h3>
                <div class="mt-2 flex items-center justify-between">
                  <p class="text-sm italic text-gray-500">Here you can add, remove or edit devices. You can perform certain actions on those devices.</p>
                </div>
              </div>
              <div>
                <ul role="list" class="mt-2 divide-y divide-gray-200 border-t border-b border-gray-200">

                  @foreach ($devicesList as $device)
                    <li class="flex items-center justify-between py-3">
                      <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M64 0C28.7 0 0 28.7 0 64V352c0 35.3 28.7 64 64 64H240l-10.7 32H160c-17.7 0-32 14.3-32 32s14.3 32 32 32H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H346.7L336 416H512c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H64zM512 64V288H64V64H512z"/></svg>
                        <p class="ml-4 text-sm font-medium text-gray-900">{{ $device }}</p>
                      </div>
                      <button type="button" data-device="{{ $device }}" class="remove-device ml-6 rounded-md bg-white text-sm font-medium text-rose-700 hover:text-rose-800 focus:outline-none focus:ring-2 focus:ring-rose-800 focus:ring-offset-2">
                        Remove
                      </button>
                    </li>
                  @endforeach

                  <li class="flex items-center justify-between py-2">
                    <div>
                      <label for="device-key" class="block text-sm font-medium text-gray-900">Add key (use this key to add devices)</label>
                      <div class="mt-1">
                        {{-- $deviceKey must be a random, server-generated token (e.g. Str::random(40)), stored per user and rotatable. --}}
                        <input type="text" id="device-key" value="{{ $deviceKey ?? '' }}" readonly autocomplete="off" class="block w-full rounded-md focus:border-rose-700 focus:ring-rose-700 sm:text-sm">
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
              <div class="flex gap-x-4">
                <button type="button" class="bg-rose-50 text-rose-700 hover:bg-rose-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium text-[13px]">Scan all devices</button>
                <button type="button" class="bg-rose-50 text-rose-700 hover:bg-rose-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium text-[13px]">Optimize all devices</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ============================ SETTINGS MODAL ============================ --}}
<div id="settingsModal" class="hidden relative z-10" aria-labelledby="settings-title" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
  <div class="fixed inset-0 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
      <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
        <div class="pointer-events-auto w-screen max-w-md">
          <form method="POST" action="{{ route('account') }}" class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl">
            @csrf
            <div class="h-0 flex-1 overflow-y-auto">
              <div class="bg-rose-700 py-6 px-4 sm:px-6">
                <div class="flex items-center justify-between">
                  <h2 class="text-lg font-medium text-white" id="settings-title">Settings</h2>
                </div>
                <div class="mt-1">
                  <p class="text-sm text-rose-300">Here you can edit settings of antivirus and this account control panel.</p>
                </div>
              </div>
              <div class="flex flex-1 flex-col justify-between">
                <div class="divide-y divide-gray-200 px-4 sm:px-6">
                  <div class="space-y-6 pt-6 pb-5">
                    <div>
                      <label for="settings-name" class="block text-sm font-medium text-gray-900">Your Name</label>
                      <div class="mt-1">
                        <input type="text" name="name" id="settings-name" value="{{ auth()->user()->name }}" required maxlength="255" autocomplete="given-name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-rose-700 focus:ring-rose-700 sm:text-sm">
                      </div>
                    </div>
                    <div>
                      <label for="settings-surname" class="block text-sm font-medium text-gray-900">Your Surname</label>
                      <div class="mt-1">
                        <input type="text" name="surname" id="settings-surname" value="{{ auth()->user()->surname }}" required maxlength="255" autocomplete="family-name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-rose-700 focus:ring-rose-700 sm:text-sm">
                      </div>
                    </div>
                    <div>
                      <label for="settings-email" class="block text-sm font-medium text-gray-900">Your Email</label>
                      <div class="mt-1">
                        <input type="email" name="email" id="settings-email" value="{{ auth()->user()->email }}" required maxlength="255" autocomplete="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-rose-700 focus:ring-rose-700 sm:text-sm">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="flex flex-shrink-0 justify-end px-4 py-4">
              <button type="button" data-target="#settingsModal" class="close-modal rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-rose-700 focus:ring-offset-2">Cancel</button>
              <button type="submit" class="ml-4 inline-flex justify-center rounded-md border border-transparent bg-rose-700 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-700 focus:ring-offset-2">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ============================ HISTORY MODAL ============================ --}}
<div id="historyModal" class="hidden relative z-10" aria-labelledby="history-title" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
  <div class="fixed inset-0 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
      <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
        <div class="pointer-events-auto w-screen max-w-md">
          <div class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl">
            <div class="h-0 flex-1 overflow-y-auto">
              <div class="bg-rose-700 py-6 px-4 sm:px-6">
                <div class="flex items-center justify-between">
                  <h2 class="text-lg font-medium text-white" id="history-title">History</h2>
                </div>
                <div class="mt-1">
                  <p class="text-sm text-rose-300">Here you can view tasks performed on this account.</p>
                </div>
              </div>
              <div class="flex flex-1 flex-col justify-between">
                <div class="divide-y divide-gray-200 px-4 sm:px-6">
                  <div class="space-y-6 pt-6 pb-5">
                    @if (!empty($history))
                      <div class="history-list">
                        <h3>Execution History:</h3>
                        <ul>
                          @foreach ($history as $entry)
                            <li>
                              <p class="text-sm">
                                Execution: {{ $entry['execute'] }} <br>
                                Last Date: {{ $entry['date'] }}
                              </p>
                            </li>
                          @endforeach
                        </ul>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="flex flex-shrink-0 justify-end px-4 py-4">
              <button type="button" data-target="#historyModal" class="close-modal rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-rose-700 focus:ring-offset-2">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const CSRF = @json(csrf_token());

  // Per-session secret shared with the local agent. The agent must reject any request
  // that does not carry a valid token and must only allow this site's origin (CORS).
  const AGENT_URL = 'http://localhost:6969/';
  const AGENT_TOKEN = @json($agentToken ?? '');

  $(document).ready(function () {

    // ---------- Modals ----------
    function toggleModal(modalId, show = true) {
      const $modal = $(modalId);
      const duration = 500;
      if (show) {
        $modal.fadeIn(duration);
        $('body').css('overflow', 'hidden');
      } else {
        $modal.fadeOut(duration);
        $('body').css('overflow', 'auto');
      }
    }

    $('.open-modal').on('click', function () {
      toggleModal($(this).data('target'));
    });

    $('.close-modal').on('click', function () {
      toggleModal($(this).data('target'), false);
    });

    // ---------- Notifications (text only, never inserted as HTML) ----------
    let counter = 0;
    const $notification = $('.notification');

    function displayNotification(title, receivedMessage, duration) {
      counter++;
      const id = 'msg-' + counter;

      let parsed = null;
      try {
        parsed = JSON.parse(receivedMessage);
      } catch (e) { /* plain-text response */ }

      const isObject = parsed !== null && typeof parsed === 'object';
      const status = isObject ? parsed.status : String(title).charAt(0).toUpperCase() + String(title).slice(1);
      const message = isObject ? parsed.message : receivedMessage;
      const removed = isObject ? parsed.removed : '';

      const $el = $(`
        <div class="mb-1 flex w-full flex-col items-center space-y-4 sm:items-end">
          <div class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
            <div class="p-4">
              <div class="flex items-start">
                <div class="flex-shrink-0"><svg class="h-6 w-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4a8 8 0 1 0 0 16 8 8 0 0 0 0-16zM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12zm5.793-4.207a1 1 0 0 1 1.414 0L12 10.586l2.793-2.793a1 1 0 1 1 1.414 1.414L13.414 12l2.793 2.793a1 1 0 0 1-1.414 1.414L12 13.414l-2.793 2.793a1 1 0 0 1-1.414-1.414L10.586 12 7.793 9.207a1 1 0 0 1 0-1.414z" fill="#DE3163"/></svg></div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                  <p class="js-status text-sm font-medium text-gray-900"></p>
                  <p class="js-message mt-1 text-sm text-gray-500"></p>
                  <p class="js-removed mt-1 text-sm text-gray-500"></p>
                </div>
              </div>
            </div>
          </div>
        </div>`).attr('id', id);

      // .text() escapes everything, so a malicious agent/server response cannot inject markup.
      $el.find('.js-status').text(status ?? '');
      $el.find('.js-message').text(message ?? '');
      $el.find('.js-removed').text(removed ?? '');

      $notification.append($el).removeClass('hidden');

      setTimeout(function () {
        $el.remove();
        if ($notification.children().length === 0) {
          $notification.addClass('hidden');
        }
      }, duration);
    }

    // ---------- Local agent ----------
    const post = (requestData) => {
      $.ajax({
        type: 'POST',
        url: AGENT_URL,
        data: requestData,
        contentType: 'text/plain',
        headers: { 'X-Agent-Token': AGENT_TOKEN },
        dataType: 'text',
        timeout: 15000
      }).done((data, status) => {
        displayNotification(status, data, 5000);
      }).fail(() => {
        displayNotification(
          'Program not opened.',
          "We can't seem to find our program running on your computer, please check if it's open or not.",
          5000
        );
      });
    };

    $('button.postdata').on('click', function () {
      const command = $(this).data('command');
      if (command) {
        post(command);
      }
    });

    // ---------- One-file scan (hash computed in the browser; the file never leaves the machine) ----------
    const dropZone = $('#drop_zone');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropZone.on(eventName, e => {
        e.preventDefault();
        e.stopPropagation();
      });
    });

    async function sha256Hex(file) {
      const buffer = await file.arrayBuffer();
      const digest = await crypto.subtle.digest('SHA-256', buffer);
      return Array.from(new Uint8Array(digest))
        .map(b => b.toString(16).padStart(2, '0'))
        .join('');
    }

    dropZone.on('drop', async e => {
      const file = e.originalEvent.dataTransfer.files[0];
      if (!file) {
        return;
      }

      if (!window.crypto || !crypto.subtle) {
        displayNotification('Error', 'Hashing needs a secure context (HTTPS or localhost).', 5000);
        return;
      }

      try {
        // SHA-256 instead of MD5: MD5 is broken, so a crafted file could share a hash with a clean one.
        const hash = await sha256Hex(file);
        toggleModal('#scanOneFileModal', false);
        post('[one_file]' + hash);
      } catch (err) {
        displayNotification('Error', 'Could not read the file.', 5000);
      }
    });

    // ---------- Devices ----------
    $(document).on('click', '.remove-device', function () {
      const device = $(this).data('device');
      if (!confirm('Remove this device from your account?')) {
        return;
      }
      $.ajax({
        type: 'POST',
        url: '/remove/device',
        data: { device: device },
        headers: { 'X-CSRF-TOKEN': CSRF },
        success: function () {
          location.reload();
        },
        error: function (error) {
          console.error(error);
        }
      });
    });

    // ---------- History ----------
    $('.history').on('click', function () {
      const operation = $(this).data('execute');
      const date = $(this).data('date');
      $.ajax({
        type: 'POST',
        url: '/history',
        data: { execute: operation, date: date },
        headers: { 'X-CSRF-TOKEN': CSRF },
        success: function () {
          $('#last' + operation).text('Performed: ' + date);
        },
        error: function (error) {
          console.error(error);
        }
      });
    });
  });
</script>
@endsection
