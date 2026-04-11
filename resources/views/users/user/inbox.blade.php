@extends('layouts.app', ['bodyClass' => 'bg-background-light text-text-light font-sans antialiased', 'headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Inbox')

@section('content')
<main class="flex-grow">
    <section class="relative overflow-hidden py-12 md:py-16 bg-gradient-to-br from-surface-light via-background-light to-surface-light">
      <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-10 left-24 w-32 h-32 bg-primary/25 rounded-full blur-3xl"></div>
          <div class="absolute bottom-10 right-24 w-40 h-40 bg-brand-blue-dark/10 rounded-full blur-3xl"></div>
        </div>
        <div class="container relative mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
              <p class="text-sm uppercase tracking-[0.2em] text-primary font-semibold">Inbox</p>
              <h1 class="text-3xl md:text-4xl font-display font-bold text-brand-blue-light mt-2">Message center for wishers &amp; donors</h1>
              <p class="text-text-muted-light mt-2 max-w-3xl">Keep every conversation close at hand. Browse recent chats, reply quickly, and see who you are talking to without leaving the page.</p>
            </div>
            <div class="flex items-center gap-3">
              <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-text-light hover:bg-gray-100">
                <span class="material-symbols-outlined">archive</span>
                Archive
              </button>
              <button id="open-new-message" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold shadow hover:shadow-md">
                <span class="material-symbols-outlined text-base">add</span>
                New Message
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-[320px_minmax(0,1.3fr)_320px] gap-6">
            <!-- Conversation list -->
            <div class="bg-surface-light rounded-2xl shadow-lg border border-gray-200">
              <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <div>
                  <p class="text-lg font-semibold text-brand-blue-light">Messaging</p>
                  <p class="text-sm text-text-muted-light">6 active conversations</p>
                </div>
                <div class="flex items-center gap-2 text-text-muted-light">
                  <button class="p-2 rounded-md hover:bg-gray-100" aria-label="Filter">
                    <span class="material-symbols-outlined">tune</span>
                  </button>
                  <button class="p-2 rounded-md hover:bg-gray-100" aria-label="More">
                    <span class="material-symbols-outlined">more_vert</span>
                  </button>
                </div>
              </div>
              <div class="px-4 pt-4 pb-2">
                <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-gray-100 text-text-muted-light">
                  <span class="material-symbols-outlined text-base">search</span>
                  <input aria-label="Search user" class="w-full bg-transparent border-0 p-0 text-sm focus:ring-0 placeholder:text-text-muted-light" placeholder="Search user" type="search" />
                  <span class="material-symbols-outlined text-base">group</span>
                </div>
              </div>
              <div class="divide-y divide-gray-100 max-h-[640px] overflow-y-auto">
                <button class="w-full text-left px-4 py-3 hover:bg-gray-50">
                  <div class="flex items-start gap-3">
                    <img alt="Brews" class="w-12 h-12 rounded-lg object-cover" src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=120&q=80" />
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="font-semibold text-brand-blue-light">Brews</p>
                        <span class="text-xs text-text-muted-light">Apr 03</span>
                      </div>
                      <p class="text-sm text-text-muted-light truncate">Hello</p>
                      <div class="flex items-center gap-2 mt-1">
                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span class="text-xs text-emerald-700 font-semibold">Online</span>
                      </div>
                    </div>
                    <span class="material-symbols-outlined text-text-muted-light">more_vert</span>
                  </div>
                </button>
                <button class="w-full text-left px-4 py-3 hover:bg-gray-50">
                  <div class="flex items-start gap-3">
                    <img alt="Pieones" class="w-12 h-12 rounded-lg object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80" />
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="font-semibold text-brand-blue-light">Pieones</p>
                        <span class="text-xs text-text-muted-light">Apr 01</span>
                      </div>
                      <p class="text-sm text-text-muted-light truncate">sdasd</p>
                      <div class="flex items-center gap-2 mt-1">
                        <span class="inline-flex h-2 w-2 rounded-full bg-amber-400"></span>
                        <span class="text-xs text-amber-700 font-semibold">Away</span>
                      </div>
                    </div>
                    <span class="material-symbols-outlined text-text-muted-light">more_vert</span>
                  </div>
                </button>
                <button class="w-full text-left px-4 py-3 hover:bg-gray-50 bg-primary/5">
                  <div class="flex items-start gap-3">
                    <img alt="Belle" class="w-12 h-12 rounded-lg object-cover" src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=120&q=80" />
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="font-semibold text-brand-blue-light">Belle</p>
                        <span class="text-xs text-text-muted-light">Dec 19</span>
                      </div>
                      <p class="text-sm text-text-muted-light truncate">Sending message from the profile…</p>
                      <div class="flex items-center gap-2 mt-1">
                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span class="text-xs text-emerald-700 font-semibold">Active</span>
                      </div>
                    </div>
                    <span class="material-symbols-outlined text-text-muted-light">more_vert</span>
                  </div>
                </button>
                <button class="w-full text-left px-4 py-3 hover:bg-gray-50">
                  <div class="flex items-start gap-3">
                    <img alt="Dhaval Vekariya" class="w-12 h-12 rounded-lg object-cover" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=120&q=80" />
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="font-semibold text-brand-blue-light">Dhaval Vekariya</p>
                        <span class="text-xs text-text-muted-light">Dec 05</span>
                      </div>
                      <p class="text-sm text-text-muted-light truncate">Hey</p>
                      <div class="flex items-center gap-2 mt-1">
                        <span class="inline-flex h-2 w-2 rounded-full bg-gray-400"></span>
                        <span class="text-xs text-text-muted-light font-semibold">Offline</span>
                      </div>
                    </div>
                    <span class="material-symbols-outlined text-text-muted-light">more_vert</span>
                  </div>
                </button>
                <button class="w-full text-left px-4 py-3 hover:bg-gray-50">
                  <div class="flex items-start gap-3">
                    <img alt="Jordan" class="w-12 h-12 rounded-lg object-cover" src="https://images.unsplash.com/photo-1463453091185-61582044d556?auto=format&fit=crop&w=120&q=80" />
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <p class="font-semibold text-brand-blue-light">Jordan</p>
                        <span class="text-xs text-text-muted-light">Nov 26</span>
                      </div>
                      <p class="text-sm text-text-muted-light truncate">Oh nice! In that case, I’ll do a quick pick up…</p>
                      <div class="flex items-center gap-2 mt-1">
                        <span class="inline-flex h-2 w-2 rounded-full bg-amber-400"></span>
                        <span class="text-xs text-amber-700 font-semibold">Away</span>
                      </div>
                    </div>
                    <span class="material-symbols-outlined text-text-muted-light">more_vert</span>
                  </div>
                </button>
              </div>
            </div>

            <!-- Chat thread -->
            <div class="bg-surface-light rounded-2xl shadow-lg border border-gray-200 flex flex-col">
              <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <div>
                  <div class="flex items-center gap-2">
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    <p class="text-lg font-semibold text-brand-blue-light" data-chat-name>Brews</p>
                  </div>
                  <p class="text-xs text-text-muted-light mt-1">Active 6m ago • Wisher</p>
                </div>
                <div class="flex items-center gap-2 text-text-muted-light">
                  <div class="relative">
                    <button class="p-2 rounded-md hover:bg-gray-100 chat-menu-trigger" aria-label="More">
                      <span class="material-symbols-outlined">more_vert</span>
                    </button>
                    <div class="chat-menu absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-xl shadow-lg py-2 hidden z-30 text-left text-text-light">
                      <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Clear Chat</button>
                      <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 text-red-600">Delete Chat</button>
                      <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Report</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6 bg-gradient-to-b from-white to-gray-50">
                <div class="text-center">
                  <p class="text-xs text-text-muted-light">Dec 17 · 11:17 AM</p>
                </div>
                <div class="flex items-start gap-3">
                  <img alt="Brews avatar" class="w-10 h-10 rounded-md object-cover" src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=120&q=80" />
                  <div class="max-w-xl">
                    <div class="inline-flex relative items-start gap-2 bg-gray-100 text-text-light px-4 py-3 rounded-2xl rounded-tl-none shadow-sm">
                      <p class="text-sm">hii</p>
                      <button class="message-action text-text-muted-light hover:text-brand-blue-light">
                        <span class="material-symbols-outlined text-base">expand_more</span>
                      </button>
                      <div class="message-menu absolute right-0 top-full mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-lg py-2 hidden z-20">
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Reply</button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Forward</button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Copy</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Delete</button>
                      </div>
                    </div>
                    <p class="text-xs text-text-muted-light mt-2">Delivered · 11:17 AM</p>
                  </div>
                </div>

                <div class="flex items-start gap-3 justify-end">
                  <div class="max-w-xl text-right">
                    <div class="inline-flex relative items-start gap-2 bg-brand-blue-light text-white px-4 py-3 rounded-2xl rounded-tr-none shadow-sm">
                      <p class="text-sm">Naiiaaksjjdjdndnnddfnohghgdbfjfrj</p>
                      <button class="message-action text-white/70 hover:text-white">
                        <span class="material-symbols-outlined text-base">expand_more</span>
                      </button>
                      <div class="message-menu absolute right-0 top-full mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-lg py-2 hidden z-20 text-left text-text-light">
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Reply</button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Forward</button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Copy</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Delete</button>
                      </div>
                    </div>
                    <p class="text-xs text-text-muted-light mt-2">Seen · 11:18 AM</p>
                  </div>
                  <img alt="You" class="w-10 h-10 rounded-md object-cover" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=120&q=80" />
                </div>

                <div class="flex items-start gap-3 justify-end">
                  <div class="max-w-xl text-right">
                    <div class="inline-flex relative items-start gap-2 bg-brand-blue-light text-white px-4 py-3 rounded-2xl rounded-tr-none shadow-sm">
                      <p class="text-sm">Yes, got it. I will prepare the drop off.</p>
                      <button class="message-action text-white/70 hover:text-white">
                        <span class="material-symbols-outlined text-base">expand_more</span>
                      </button>
                      <div class="message-menu absolute right-0 top-full mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-lg py-2 hidden z-20 text-left text-text-light">
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Reply</button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Forward</button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Copy</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Delete</button>
                      </div>
                    </div>
                    <p class="text-xs text-text-muted-light mt-2">11:20 AM</p>
                  </div>
                  <img alt="You" class="w-10 h-10 rounded-md object-cover" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=120&q=80" />
                </div>

                <div class="flex items-start gap-3">
                  <img alt="Brews avatar" class="w-10 h-10 rounded-md object-cover" src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=120&q=80" />
                  <div class="max-w-xl">
                    <div class="inline-flex relative items-start gap-2 bg-gray-100 text-text-light px-4 py-3 rounded-2xl rounded-tl-none shadow-sm">
                      <p class="text-sm">Great! Can you send a quick picture of the items?</p>
                      <button class="message-action text-text-muted-light hover:text-brand-blue-light">
                        <span class="material-symbols-outlined text-base">expand_more</span>
                      </button>
                      <div class="message-menu absolute right-0 top-full mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-lg py-2 hidden z-20">
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Reply</button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Forward</button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Copy</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Delete</button>
                      </div>
                    </div>
                    <p class="text-xs text-text-muted-light mt-2">11:21 AM</p>
                  </div>
                </div>

                <div class="flex items-start gap-3 justify-end">
                  <div class="max-w-xl text-right space-y-2">
                    <div class="inline-flex relative items-start gap-2 bg-brand-blue-light text-white px-4 py-3 rounded-2xl rounded-tr-none shadow-sm">
                      <p class="text-sm">Here you go! You can keep the blankets.</p>
                      <button class="message-action text-white/70 hover:text-white">
                        <span class="material-symbols-outlined text-base">expand_more</span>
                      </button>
                      <div class="message-menu absolute right-0 top-full mt-2 w-40 bg-white border border-gray-200 rounded-xl shadow-lg py-2 hidden z-20 text-left text-text-light">
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Reply</button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Forward</button>
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">Copy</button>
                        <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Delete</button>
                      </div>
                    </div>
                    <div class="flex flex-wrap gap-2 justify-end">
                      <img alt="Donation item" class="w-28 h-24 rounded-lg object-cover shadow-sm"
                        src="https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=260&q=80" />
                      <img alt="Donation item" class="w-28 h-24 rounded-lg object-cover shadow-sm"
                        src="https://images.unsplash.com/photo-1473186578172-c141e6798cf4?auto=format&fit=crop&w=260&q=80" />
                    </div>
                    <p class="text-xs text-text-muted-light">11:23 AM</p>
                  </div>
                  <img alt="You" class="w-10 h-10 rounded-md object-cover" src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=120&q=80" />
                </div>
              </div>

              <div class="border-t border-gray-200 p-4">
                <div class="flex items-center gap-3">
                  <button class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-text-muted-light" aria-label="Add attachment">
                    <span class="material-symbols-outlined">attach_file</span>
                  </button>
                  <div class="relative">
                    <button id="emoji-trigger" class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-text-muted-light" aria-label="Add emoji">
                      <span class="material-symbols-outlined">mood</span>
                    </button>
                    <div id="emoji-menu" class="absolute bottom-full left-0 mb-2 w-60 bg-white border border-gray-200 rounded-xl shadow-lg p-3 hidden z-40">
                      <div class="flex items-center justify-between mb-2 px-1">
                        <p class="text-xs font-semibold text-text-muted-light">All smileys</p>
                        <span class="text-[10px] text-text-muted-light">Scrollable</span>
                      </div>
                      <div id="emoji-grid" class="grid grid-cols-6 gap-2 text-xl max-h-60 overflow-y-auto pr-1"></div>
                    </div>
                  </div>
                  <div class="flex-1">
                    <label class="sr-only" for="message">Write a message</label>
                    <textarea class="w-full rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary px-4 py-3 text-sm resize-none"
                      id="message" placeholder="Write a message" rows="2"></textarea>
                  </div>
                  <button class="inline-flex items-center justify-center px-4 py-3 bg-brand-blue-light text-white rounded-xl font-semibold shadow hover:shadow-md"
                    aria-label="Send message">
                    <span class="material-symbols-outlined">send</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Profile -->
            <aside class="bg-surface-light rounded-2xl shadow-lg border border-gray-200 p-6 space-y-6">
              <div class="flex flex-col items-center text-center">
                <div class="w-32 h-32 rounded-full overflow-hidden shadow">
                  <img alt="Brews portrait" class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=320&q=80" />
                </div>
                <div class="flex items-center gap-3 mt-4">
                  <button class="px-4 py-2 rounded-lg bg-brand-blue-light text-white font-semibold shadow hover:shadow-md">View Profile</button>
                </div>
              </div>
            </aside>
          </div>
        </div>
      </section>
    </main>
@endsection
