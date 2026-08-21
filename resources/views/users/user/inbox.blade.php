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
          <button id="open-new-message" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold shadow hover:shadow-md">
            <span class="material-symbols-outlined text-base">add</span>
            New Message
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-[320px_minmax(0,1.3fr)_320px] gap-6">
        <!-- Conversation list -->
        <div class="bg-surface-light rounded-2xl shadow-lg border border-gray-200 flex flex-col">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <div>
              <p class="text-lg font-semibold text-brand-blue-light">Messaging</p>
              <p class="text-sm text-text-muted-light"><span id="conversation-count">0</span> conversations</p>
            </div>
            <div class="flex items-center gap-2 text-text-muted-light">
              <button id="new-message-inline" class="p-2 rounded-md hover:bg-gray-100" aria-label="New message">
                <span class="material-symbols-outlined">edit_square</span>
              </button>
            </div>
          </div>
          <div class="px-4 pt-4 pb-2">
            <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-gray-100 text-text-muted-light">
              <span class="material-symbols-outlined text-base">search</span>
              <input id="conversation-search" aria-label="Search conversations" class="w-full bg-transparent border-0 p-0 text-sm focus:ring-0 placeholder:text-text-muted-light" placeholder="Search conversations" type="search" />
            </div>
          </div>
          <div id="conversation-list" class="divide-y divide-gray-100 max-h-[640px] overflow-y-auto"></div>
          <div id="conversation-empty" class="hidden px-6 py-12 text-center text-text-muted-light">
            <span class="material-symbols-outlined text-4xl text-gray-300">forum</span>
            <p class="mt-2 text-sm">No conversations yet.</p>
            <button id="empty-new-message" class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-brand-blue-light hover:underline">
              <span class="material-symbols-outlined text-base">add</span> Start a new message
            </button>
          </div>
        </div>

        <!-- Chat thread -->
        <div class="bg-surface-light rounded-2xl shadow-lg border border-gray-200 flex flex-col min-h-[560px]">
          <div id="chat-header" class="flex items-center justify-between px-6 py-4 border-b border-gray-200 hidden">
            <div>
              <div class="flex items-center gap-2">
                <span id="chat-status-dot" class="inline-flex h-2.5 w-2.5 rounded-full bg-gray-400"></span>
                <p class="text-lg font-semibold text-brand-blue-light" id="chat-name">&nbsp;</p>
              </div>
              <p class="text-xs text-text-muted-light mt-1" id="chat-status-label">Offline</p>
            </div>
            <div class="relative">
              <button id="chat-options-trigger" type="button" class="p-2 rounded-md hover:bg-gray-100 text-text-muted-light" aria-label="Conversation options">
                <span class="material-symbols-outlined">more_vert</span>
              </button>
              <div id="chat-options-menu" class="hidden absolute right-0 mt-1 w-56 bg-white border border-gray-200 rounded-xl shadow-lg py-2 text-left z-40">
                <button id="chat-delete-conversation" type="button" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                  <span class="material-symbols-outlined text-base">delete</span> Delete conversation
                </button>
              </div>
            </div>
          </div>

          <div id="messages-container" class="flex-1 overflow-y-auto px-6 py-6 space-y-4 bg-gradient-to-b from-white to-gray-50"></div>

          <div id="chat-empty" class="flex-1 flex flex-col items-center justify-center text-center text-text-muted-light px-6">
            <span class="material-symbols-outlined text-5xl text-gray-300">chat</span>
            <p class="mt-3 text-sm">Select a conversation to start chatting.</p>
          </div>

          <div class="border-t border-gray-200 p-4">
            <div id="reply-preview" class="hidden items-start gap-3 mb-3 px-3 py-2 rounded-xl bg-gray-100 border-l-4 border-brand-blue-light">
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-brand-blue-light">Replying to <span id="reply-preview-name"></span></p>
                <p id="reply-preview-body" class="text-sm text-text-muted-light truncate"></p>
              </div>
              <button id="reply-cancel" type="button" class="p-1 rounded-md hover:bg-gray-200 text-text-muted-light" aria-label="Cancel reply">
                <span class="material-symbols-outlined text-base">close</span>
              </button>
            </div>
            <div class="flex items-end gap-3">
              <div class="relative">
                <button id="emoji-trigger" type="button" class="p-2 rounded-lg border border-gray-200 hover:bg-gray-100 text-text-muted-light disabled:opacity-40" aria-label="Add emoji">
                  <span class="material-symbols-outlined">mood</span>
                </button>
                <div id="emoji-menu" class="absolute bottom-full left-0 mb-2 w-60 bg-white border border-gray-200 rounded-xl shadow-lg p-3 hidden z-40">
                  <div class="flex items-center justify-between mb-2 px-1">
                    <p class="text-xs font-semibold text-text-muted-light">Emojis</p>
                  </div>
                  <div id="emoji-grid" class="grid grid-cols-6 gap-2 text-xl max-h-52 overflow-y-auto pr-1"></div>
                </div>
              </div>
              <div class="flex-1">
                <label class="sr-only" for="message-input">Write a message</label>
                <textarea class="w-full rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary focus:border-primary px-4 py-3 text-sm resize-none disabled:bg-gray-100"
                  id="message-input" placeholder="Write a message" rows="2" disabled></textarea>
              </div>
              <button id="send-button" type="button" class="inline-flex items-center justify-center px-4 py-3 bg-brand-blue-light text-white rounded-xl font-semibold shadow hover:shadow-md disabled:opacity-40"
                aria-label="Send message" disabled>
                <span class="material-symbols-outlined">send</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Profile -->
        <aside class="bg-surface-light rounded-2xl shadow-lg border border-gray-200 p-6">
          <div id="profile-panel" class="hidden flex-col items-center text-center">
            <div class="w-32 h-32 rounded-full overflow-hidden shadow bg-gray-100">
              <img id="profile-avatar" alt="Profile" class="w-full h-full object-cover" src="" />
            </div>
            <p id="profile-name" class="mt-4 text-lg font-semibold text-brand-blue-light">&nbsp;</p>
            <div class="flex items-center gap-2 mt-1">
              <span id="profile-status-dot" class="inline-flex h-2 w-2 rounded-full bg-gray-400"></span>
              <span id="profile-status-label" class="text-xs font-semibold text-text-muted-light">Offline</span>
            </div>
            <a id="profile-view-link" href="#" target="_blank" rel="noopener"
              class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-blue-light text-white text-sm font-semibold shadow hover:shadow-md">
              <span class="material-symbols-outlined text-base">account_circle</span> View Profile
            </a>
            <p id="profile-location" class="text-sm text-text-muted-light mt-3 hidden"></p>
            <p id="profile-about" class="text-sm text-text-muted-light mt-3 leading-relaxed hidden"></p>
          </div>
          <div id="profile-empty" class="flex flex-col items-center justify-center text-center text-text-muted-light py-10">
            <span class="material-symbols-outlined text-4xl text-gray-300">person</span>
            <p class="mt-2 text-sm">Details appear here.</p>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <!-- New message modal -->
  <div id="new-message-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <p class="text-lg font-semibold text-brand-blue-light">New message</p>
        <button id="close-new-message" class="p-1 rounded-md hover:bg-gray-100 text-text-muted-light" aria-label="Close">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <div class="px-6 py-4">
        <div class="flex items-center gap-2 px-4 py-3 rounded-xl bg-gray-100 text-text-muted-light">
          <span class="material-symbols-outlined text-base">search</span>
          <input id="user-search-input" aria-label="Search users" class="w-full bg-transparent border-0 p-0 text-sm focus:ring-0 placeholder:text-text-muted-light" placeholder="Search people by name" type="search" autocomplete="off" />
        </div>
        <div id="user-search-results" class="mt-4 max-h-72 overflow-y-auto divide-y divide-gray-100"></div>
      </div>
    </div>
  </div>

  <!-- Shared per-message action menu -->
  <div id="message-menu" class="fixed hidden z-50 w-40 bg-white border border-gray-200 rounded-xl shadow-lg py-2 text-left text-text-light">
    <button data-action="reply" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 flex items-center gap-2">
      <span class="material-symbols-outlined text-base">reply</span> Reply
    </button>
    <button data-action="copy" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 flex items-center gap-2">
      <span class="material-symbols-outlined text-base">content_copy</span> Copy
    </button>
    <button data-action="delete" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
      <span class="material-symbols-outlined text-base">delete</span> Delete
    </button>
  </div>
</main>

@php
  $chatBootstrap = [
    'conversations' => $conversations,
    'activeConversationId' => (int) $selectedConversationId,
    'messages' => $selectedMessages,
    'participant' => $participant,
    'currentUserId' => auth()->id(),
  ];
@endphp
<script type="application/json" id="chat-bootstrap">
  @json($chatBootstrap)
</script>

<script>
  (function () {
    const bootstrap = JSON.parse(document.getElementById('chat-bootstrap').textContent);
    const ROUTES = {
      threads: @json(route('chat.threads')),
      usersSearch: @json(route('chat.users.search')),
      openConversation: @json(route('chat.conversations.open')),
      conversationsBase: @json(url('chat/conversations')),
      membersBase: @json(url('members')),
      heartbeat: @json(route('chat.heartbeat')),
    };
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const CURRENT_USER_ID = bootstrap.currentUserId;

    // ---- State ----
    let conversations = bootstrap.conversations || [];
    let activeConversationId = bootstrap.activeConversationId || 0;
    let lastMessageId = 0;
    let lastReadId = 0;
    const renderedIds = new Set();
    let searchTerm = '';
    let replyToId = 0;
    let menuTargetId = 0;

    // ---- DOM ----
    const listEl = document.getElementById('conversation-list');
    const listEmptyEl = document.getElementById('conversation-empty');
    const countEl = document.getElementById('conversation-count');
    const messagesEl = document.getElementById('messages-container');
    const chatHeaderEl = document.getElementById('chat-header');
    const chatEmptyEl = document.getElementById('chat-empty');
    const chatNameEl = document.getElementById('chat-name');
    const chatStatusDot = document.getElementById('chat-status-dot');
    const chatStatusLabel = document.getElementById('chat-status-label');
    const inputEl = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-button');
    const profilePanel = document.getElementById('profile-panel');
    const profileEmpty = document.getElementById('profile-empty');
    const chatOptionsTrigger = document.getElementById('chat-options-trigger');
    const chatOptionsMenu = document.getElementById('chat-options-menu');
    const chatDeleteConversationBtn = document.getElementById('chat-delete-conversation');

    const modal = document.getElementById('new-message-modal');
    const userSearchInput = document.getElementById('user-search-input');
    const userSearchResults = document.getElementById('user-search-results');

    const msgMenu = document.getElementById('message-menu');
    const replyPreview = document.getElementById('reply-preview');
    const replyPreviewName = document.getElementById('reply-preview-name');
    const replyPreviewBody = document.getElementById('reply-preview-body');
    const DELETED_TEXT = 'This message was deleted';

    // ---- Helpers ----
    function jsonHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      };
    }

    function isNearBottom() {
      return messagesEl.scrollHeight - messagesEl.scrollTop - messagesEl.clientHeight < 120;
    }

    function scrollToBottom() {
      messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function buildAvatar(src, alt, sizeClass) {
      const img = document.createElement('img');
      img.src = src;
      img.alt = alt || '';
      img.className = sizeClass + ' rounded-lg object-cover bg-gray-100';
      img.loading = 'lazy';
      return img;
    }

    // ---- Conversation list ----
    function renderConversations() {
      const term = searchTerm.trim().toLowerCase();
      const visible = conversations.filter((c) => !term || (c.name || '').toLowerCase().includes(term));

      countEl.textContent = conversations.length;
      listEl.innerHTML = '';

      if (!conversations.length) {
        listEmptyEl.classList.remove('hidden');
        return;
      }
      listEmptyEl.classList.add('hidden');

      visible.forEach((c) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'w-full text-left px-4 py-3 hover:bg-gray-50 focus:outline-none' + (c.id === activeConversationId ? ' bg-primary/5' : '');
        btn.dataset.conversationId = c.id;

        const row = document.createElement('div');
        row.className = 'flex items-start gap-3';

        row.appendChild(buildAvatar(c.avatar, c.name, 'w-12 h-12'));

        const main = document.createElement('div');
        main.className = 'flex-1 min-w-0';

        const top = document.createElement('div');
        top.className = 'flex items-center justify-between gap-2';
        const nameEl = document.createElement('p');
        nameEl.className = 'font-semibold text-brand-blue-light truncate';
        nameEl.textContent = c.name;
        const timeEl = document.createElement('span');
        timeEl.className = 'text-xs text-text-muted-light shrink-0';
        timeEl.textContent = c.last_message_at || '';
        top.appendChild(nameEl);
        top.appendChild(timeEl);

        const preview = document.createElement('p');
        preview.className = 'text-sm text-text-muted-light truncate';
        preview.textContent = c.last_message || 'No messages yet';

        const meta = document.createElement('div');
        meta.className = 'flex items-center justify-between gap-2 mt-1';

        const statusWrap = document.createElement('div');
        statusWrap.className = 'flex items-center gap-2';
        const dot = document.createElement('span');
        dot.className = 'inline-flex h-2 w-2 rounded-full ' + (c.is_online ? 'bg-emerald-500' : 'bg-gray-400');
        const statusText = document.createElement('span');
        statusText.className = 'text-xs font-semibold ' + (c.is_online ? 'text-emerald-700' : 'text-text-muted-light');
        statusText.textContent = c.status_label || (c.is_online ? 'Online' : 'Offline');
        statusWrap.appendChild(dot);
        statusWrap.appendChild(statusText);
        meta.appendChild(statusWrap);

        if (c.unread_count > 0) {
          const badge = document.createElement('span');
          badge.className = 'inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-brand-blue-light text-white text-[11px] font-semibold';
          badge.textContent = c.unread_count > 99 ? '99+' : c.unread_count;
          meta.appendChild(badge);
        }

        main.appendChild(top);
        main.appendChild(preview);
        main.appendChild(meta);
        row.appendChild(main);
        btn.appendChild(row);
        listEl.appendChild(btn);
      });
    }

    listEl.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-conversation-id]');
      if (!btn) return;
      const id = parseInt(btn.dataset.conversationId, 10);
      if (id && id !== activeConversationId) openConversation(id, true);
    });

    // ---- Messages ----
    function messageRow(msg) {
      const wrap = document.createElement('div');
      wrap.className = 'flex items-start gap-3' + (msg.is_mine ? ' justify-end' : '');
      wrap.dataset.messageId = msg.id;
      wrap.dataset.mine = msg.is_mine ? '1' : '0';
      wrap.dataset.deleted = msg.is_deleted ? '1' : '0';
      wrap.dataset.sender = msg.sender_name || '';

      const avatar = buildAvatar(msg.sender_avatar, msg.sender_name, 'w-10 h-10');
      avatar.className = 'w-10 h-10 rounded-md object-cover bg-gray-100 shrink-0';

      const col = document.createElement('div');
      col.className = 'max-w-xl min-w-0' + (msg.is_mine ? ' text-right' : '');

      const line = document.createElement('div');
      line.className = 'group flex items-center gap-1' + (msg.is_mine ? ' justify-end' : '');

      const bubble = document.createElement('div');
      bubble.className = 'msg-bubble inline-block px-4 py-3 rounded-2xl shadow-sm text-left whitespace-pre-wrap break-words ' +
        (msg.is_mine ? 'bg-brand-blue-light text-white rounded-tr-none' : 'bg-gray-100 text-text-light rounded-tl-none');
      fillBubble(bubble, msg);

      if (!msg.is_deleted) {
        const trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'message-action shrink-0 p-1 rounded-md text-text-muted-light hover:bg-gray-100 opacity-0 group-hover:opacity-100 focus:opacity-100 transition';
        trigger.setAttribute('aria-label', 'Message actions');
        trigger.innerHTML = '<span class="material-symbols-outlined text-base">more_vert</span>';
        if (msg.is_mine) { line.appendChild(trigger); line.appendChild(bubble); }
        else { line.appendChild(bubble); line.appendChild(trigger); }
      } else {
        line.appendChild(bubble);
      }

      const stamp = document.createElement('p');
      stamp.className = 'text-xs text-text-muted-light mt-2';
      stamp.dataset.stamp = '1';
      stamp.textContent = statusText(msg);

      col.appendChild(line);
      col.appendChild(stamp);

      if (msg.is_mine) {
        wrap.appendChild(col);
        wrap.appendChild(avatar);
      } else {
        wrap.appendChild(avatar);
        wrap.appendChild(col);
      }
      return wrap;
    }

    function fillBubble(bubble, msg) {
      bubble.innerHTML = '';
      if (!msg.is_deleted && msg.reply_to) {
        const quote = document.createElement('button');
        quote.type = 'button';
        quote.className = 'reply-quote block w-full text-left mb-2 pl-2 py-0.5 border-l-2 rounded-sm ' +
          (msg.is_mine ? 'border-white/60 hover:bg-white/10' : 'border-brand-blue-light hover:bg-black/5');
        quote.dataset.jump = msg.reply_to.id;
        const qn = document.createElement('span');
        qn.className = 'block text-xs font-semibold ' + (msg.is_mine ? 'text-white/90' : 'text-brand-blue-light');
        qn.textContent = msg.reply_to.sender_name;
        const qb = document.createElement('span');
        qb.dataset.quoteBody = '1';
        qb.className = 'block text-xs truncate ' + (msg.is_mine ? 'text-white/70' : 'text-text-muted-light') +
          (msg.reply_to.is_deleted ? ' italic' : '');
        qb.textContent = msg.reply_to.is_deleted ? DELETED_TEXT : msg.reply_to.body;
        quote.appendChild(qn);
        quote.appendChild(qb);
        bubble.appendChild(quote);
      }
      const body = document.createElement('p');
      body.className = 'message-body text-sm' + (msg.is_deleted ? ' italic opacity-80' : '');
      body.textContent = msg.is_deleted ? DELETED_TEXT : msg.body;
      bubble.appendChild(body);
    }

    function statusText(msg) {
      if (msg.is_deleted || !msg.is_mine) return msg.created_at || '';
      const seen = msg.id <= lastReadId;
      return (msg.created_at || '') + ' · ' + (seen ? 'Seen' : 'Sent');
    }

    function appendMessage(msg) {
      if (renderedIds.has(msg.id)) return;
      renderedIds.add(msg.id);
      if (msg.id > lastMessageId) lastMessageId = msg.id;
      messagesEl.appendChild(messageRow(msg));
    }

    function renderMessages(list) {
      messagesEl.innerHTML = '';
      renderedIds.clear();
      lastMessageId = 0;
      lastReadId = 0;
      (list || []).forEach((m) => {
        if (m.is_mine && !m.is_deleted && m.read_at && m.id > lastReadId) lastReadId = m.id;
      });
      (list || []).forEach(appendMessage);
      refreshSeenStatus();
      scrollToBottom();
    }

    function refreshSeenStatus() {
      messagesEl.querySelectorAll('[data-mine="1"]').forEach((row) => {
        if (row.dataset.deleted === '1') return;
        const id = parseInt(row.dataset.messageId, 10);
        const stamp = row.querySelector('[data-stamp="1"]');
        if (!stamp) return;
        const base = stamp.textContent.split(' · ')[0];
        stamp.textContent = base + ' · ' + (id <= lastReadId ? 'Seen' : 'Sent');
      });
    }

    function getRow(id) {
      return messagesEl.querySelector('[data-message-id="' + id + '"]');
    }

    function markMessageDeleted(id) {
      const row = getRow(id);
      if (row && row.dataset.deleted !== '1') {
        row.dataset.deleted = '1';
        const bubble = row.querySelector('.msg-bubble');
        if (bubble) fillBubble(bubble, { is_deleted: true });
        const trigger = row.querySelector('.message-action');
        if (trigger) trigger.remove();
        const stamp = row.querySelector('[data-stamp="1"]');
        if (stamp) stamp.textContent = stamp.textContent.split(' · ')[0];
      }
      // Update any reply quotes that point at this message.
      messagesEl.querySelectorAll('.reply-quote[data-jump="' + id + '"] [data-quote-body="1"]').forEach((el) => {
        el.textContent = DELETED_TEXT;
        el.classList.add('italic');
      });
      if (replyToId === id) clearReply();
    }

    function jumpToMessage(id) {
      const row = getRow(id);
      if (!row) return;
      row.scrollIntoView({ behavior: 'smooth', block: 'center' });
      const bubble = row.querySelector('.msg-bubble');
      if (bubble) {
        bubble.classList.add('ring-2', 'ring-primary');
        setTimeout(() => bubble.classList.remove('ring-2', 'ring-primary'), 1500);
      }
    }

    // ---- Per-message action menu ----
    function openMsgMenu(trigger) {
      const row = trigger.closest('[data-message-id]');
      if (!row) return;
      menuTargetId = parseInt(row.dataset.messageId, 10);
      const isMine = row.dataset.mine === '1';
      msgMenu.querySelector('[data-action="delete"]').classList.toggle('hidden', !isMine);

      msgMenu.classList.remove('hidden');
      const rect = trigger.getBoundingClientRect();
      const menuW = 160;
      const menuH = msgMenu.offsetHeight || 130;
      let left = Math.min(rect.left, window.innerWidth - menuW - 8);
      let top = rect.bottom + 4;
      if (top + menuH > window.innerHeight) top = Math.max(8, rect.top - menuH - 4);
      msgMenu.style.left = Math.max(8, left) + 'px';
      msgMenu.style.top = top + 'px';
    }

    function closeMsgMenu() {
      msgMenu.classList.add('hidden');
      menuTargetId = 0;
    }

    messagesEl.addEventListener('click', (e) => {
      const trig = e.target.closest('.message-action');
      if (trig) { e.stopPropagation(); openMsgMenu(trig); return; }
      const quote = e.target.closest('.reply-quote');
      if (quote) jumpToMessage(parseInt(quote.dataset.jump, 10));
    });
    messagesEl.addEventListener('scroll', closeMsgMenu);

    msgMenu.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-action]');
      if (!btn) return;
      const action = btn.dataset.action;
      const id = menuTargetId;
      closeMsgMenu();
      if (!id) return;
      if (action === 'reply') setReply(id);
      else if (action === 'copy') copyMessage(id);
      else if (action === 'delete') deleteMessage(id);
    });

    document.addEventListener('click', (e) => {
      if (!msgMenu.contains(e.target) && !e.target.closest('.message-action')) closeMsgMenu();
    });

    // ---- Reply ----
    function setReply(id) {
      const row = getRow(id);
      if (!row || row.dataset.deleted === '1') return;
      replyToId = id;
      replyPreviewName.textContent = row.dataset.mine === '1' ? 'yourself' : (row.dataset.sender || 'message');
      replyPreviewBody.textContent = row.querySelector('.message-body')?.textContent || '';
      replyPreview.classList.remove('hidden');
      replyPreview.classList.add('flex');
      inputEl.focus();
    }

    function clearReply() {
      replyToId = 0;
      replyPreview.classList.add('hidden');
      replyPreview.classList.remove('flex');
    }

    document.getElementById('reply-cancel').addEventListener('click', clearReply);

    function copyMessage(id) {
      const row = getRow(id);
      const text = row?.querySelector('.message-body')?.textContent || '';
      if (!text) return;
      if (navigator.clipboard?.writeText) navigator.clipboard.writeText(text).catch(() => {});
    }

    async function deleteMessage(id) {
      if (!window.confirm('Delete this message?')) return;
      try {
        const res = await fetch(`${ROUTES.conversationsBase}/${activeConversationId}/messages/${id}`, {
          method: 'DELETE',
          headers: jsonHeaders(),
        });
        if (res.ok) {
          markMessageDeleted(id);
          pollThreads();
        }
      } catch (e) { /* ignore */ }
    }

    // ---- Participant / header / profile ----
    function renderParticipant(p) {
      if (!p || !p.id) {
        chatHeaderEl.classList.add('hidden');
        profilePanel.classList.add('hidden');
        profilePanel.classList.remove('flex');
        profileEmpty.classList.remove('hidden');
        return;
      }

      chatHeaderEl.classList.remove('hidden');
      chatNameEl.textContent = p.name;
      chatStatusDot.className = 'inline-flex h-2.5 w-2.5 rounded-full ' + (p.is_online ? 'bg-emerald-500' : 'bg-gray-400');
      chatStatusLabel.textContent = p.status_label || (p.is_online ? 'Online' : 'Offline');

      profileEmpty.classList.add('hidden');
      profilePanel.classList.remove('hidden');
      profilePanel.classList.add('flex');
      document.getElementById('profile-avatar').src = p.avatar;
      document.getElementById('profile-avatar').alt = p.name;
      document.getElementById('profile-name').textContent = p.name;
      const viewLink = document.getElementById('profile-view-link');
      if (viewLink) viewLink.href = ROUTES.membersBase + '/' + p.id;
      document.getElementById('profile-status-dot').className = 'inline-flex h-2 w-2 rounded-full ' + (p.is_online ? 'bg-emerald-500' : 'bg-gray-400');
      const psl = document.getElementById('profile-status-label');
      psl.textContent = p.status_label || (p.is_online ? 'Online' : 'Offline');
      psl.className = 'text-xs font-semibold ' + (p.is_online ? 'text-emerald-700' : 'text-text-muted-light');

      const locEl = document.getElementById('profile-location');
      if (p.location) { locEl.textContent = p.location; locEl.classList.remove('hidden'); }
      else { locEl.classList.add('hidden'); }

      const aboutEl = document.getElementById('profile-about');
      if (p.about) { aboutEl.textContent = p.about; aboutEl.classList.remove('hidden'); }
      else { aboutEl.classList.add('hidden'); }
    }

    function updateHeaderPresence(p) {
      if (!p || !p.id) return;
      chatStatusDot.className = 'inline-flex h-2.5 w-2.5 rounded-full ' + (p.is_online ? 'bg-emerald-500' : 'bg-gray-400');
      chatStatusLabel.textContent = p.status_label || (p.is_online ? 'Online' : 'Offline');
      const dot = document.getElementById('profile-status-dot');
      const psl = document.getElementById('profile-status-label');
      if (dot) dot.className = 'inline-flex h-2 w-2 rounded-full ' + (p.is_online ? 'bg-emerald-500' : 'bg-gray-400');
      if (psl) {
        psl.textContent = p.status_label || (p.is_online ? 'Online' : 'Offline');
        psl.className = 'text-xs font-semibold ' + (p.is_online ? 'text-emerald-700' : 'text-text-muted-light');
      }
    }

    function setComposerEnabled(enabled) {
      inputEl.disabled = !enabled;
      sendBtn.disabled = !enabled;
    }

    // ---- Conversation options (delete conversation) ----
    chatOptionsTrigger.addEventListener('click', (e) => {
      e.stopPropagation();
      chatOptionsMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
      if (!chatOptionsMenu.contains(e.target) && e.target !== chatOptionsTrigger) {
        chatOptionsMenu.classList.add('hidden');
      }
    });

    function resetChatView() {
      activeConversationId = 0;
      messagesEl.innerHTML = '';
      messagesEl.classList.add('hidden');
      chatHeaderEl.classList.add('hidden');
      chatEmptyEl.classList.remove('hidden');
      setComposerEnabled(false);
      profilePanel.classList.add('hidden');
      profilePanel.classList.remove('flex');
      profileEmpty.classList.remove('hidden');

      if (window.history) {
        const url = new URL(window.location.href);
        url.searchParams.delete('conversation');
        window.history.replaceState({}, '', url);
      }
    }

    chatDeleteConversationBtn.addEventListener('click', async () => {
      chatOptionsMenu.classList.add('hidden');
      if (!activeConversationId) return;
      if (!window.confirm('Delete this conversation? It will be removed from your inbox.')) return;

      const idToDelete = activeConversationId;
      try {
        const res = await fetch(`${ROUTES.conversationsBase}/${idToDelete}`, {
          method: 'DELETE',
          headers: jsonHeaders(),
        });
        if (!res.ok) return;

        conversations = conversations.filter((c) => c.id !== idToDelete);
        resetChatView();
        renderConversations();
      } catch (e) { /* ignore */ }
    });

    // ---- Open a conversation ----
    async function openConversation(id, pushHistory) {
      activeConversationId = id;
      clearReply();
      closeMsgMenu();
      renderConversations();
      chatEmptyEl.classList.add('hidden');
      messagesEl.classList.remove('hidden');
      setComposerEnabled(true);

      // Mark read locally so the badge clears immediately.
      const conv = conversations.find((c) => c.id === id);
      if (conv) conv.unread_count = 0;
      renderConversations();

      if (pushHistory && window.history) {
        const url = new URL(window.location.href);
        url.searchParams.set('conversation', id);
        window.history.replaceState({}, '', url);
      }

      try {
        const res = await fetch(`${ROUTES.conversationsBase}/${id}/messages`, { headers: jsonHeaders() });
        if (!res.ok) return;
        const data = await res.json();
        lastReadId = data.last_read_id || 0;
        renderParticipant(data.participant);
        renderMessages(data.messages);
        inputEl.focus();
      } catch (e) { /* ignore */ }
    }

    // ---- Sending ----
    async function sendMessage() {
      const body = inputEl.value.trim();
      if (!body || !activeConversationId) return;
      const replyId = replyToId || null;
      inputEl.value = '';
      autoGrow();
      clearReply();
      setComposerEnabled(false);

      try {
        const res = await fetch(`${ROUTES.conversationsBase}/${activeConversationId}/messages`, {
          method: 'POST',
          headers: jsonHeaders(),
          body: JSON.stringify({ body, reply_to_id: replyId }),
        });
        if (res.ok) {
          const data = await res.json();
          appendMessage(data.message);
          scrollToBottom();
          pollThreads();
        } else {
          inputEl.value = body; // restore on failure
        }
      } catch (e) {
        inputEl.value = body;
      } finally {
        setComposerEnabled(true);
        inputEl.focus();
      }
    }

    sendBtn.addEventListener('click', sendMessage);
    inputEl.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    });

    function autoGrow() {
      inputEl.style.height = 'auto';
      inputEl.style.height = Math.min(inputEl.scrollHeight, 140) + 'px';
    }
    inputEl.addEventListener('input', autoGrow);

    // ---- Polling ----
    async function pollMessages() {
      if (!activeConversationId) return;
      try {
        const res = await fetch(`${ROUTES.conversationsBase}/${activeConversationId}/messages?after=${lastMessageId}`, { headers: jsonHeaders() });
        if (!res.ok) return;
        const data = await res.json();
        const stick = isNearBottom();
        (data.messages || []).forEach(appendMessage);
        (data.deleted_ids || []).forEach(markMessageDeleted);
        if (typeof data.last_read_id === 'number' && data.last_read_id !== lastReadId) {
          lastReadId = data.last_read_id;
          refreshSeenStatus();
        }
        updateHeaderPresence(data.participant);
        if (stick) scrollToBottom();
      } catch (e) { /* ignore */ }
    }

    async function pollThreads() {
      try {
        const res = await fetch(ROUTES.threads, { headers: jsonHeaders() });
        if (!res.ok) return;
        const data = await res.json();
        conversations = data.conversations || [];
        // Active conversation is being read; keep its badge clear.
        const active = conversations.find((c) => c.id === activeConversationId);
        if (active) active.unread_count = 0;
        renderConversations();
      } catch (e) { /* ignore */ }
    }

    async function heartbeat() {
      try {
        await fetch(ROUTES.heartbeat, { method: 'POST', headers: jsonHeaders() });
      } catch (e) { /* ignore */ }
    }

    // ---- New message modal ----
    function openModal() {
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      userSearchInput.value = '';
      userSearchResults.innerHTML = '';
      userSearchInput.focus();
      searchUsers('');
    }
    function closeModal() {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    let searchTimer = null;
    function searchUsers(q) {
      const url = ROUTES.usersSearch + '?q=' + encodeURIComponent(q);
      fetch(url, { headers: jsonHeaders() })
        .then((r) => r.ok ? r.json() : { users: [] })
        .then((data) => renderUserResults(data.users || []))
        .catch(() => {});
    }

    function renderUserResults(users) {
      userSearchResults.innerHTML = '';
      if (!users.length) {
        const empty = document.createElement('p');
        empty.className = 'text-sm text-text-muted-light py-6 text-center';
        empty.textContent = 'No people found.';
        userSearchResults.appendChild(empty);
        return;
      }
      users.forEach((u) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'w-full flex items-center gap-3 px-2 py-3 hover:bg-gray-50 text-left rounded-lg';
        btn.dataset.userId = u.id;
        btn.appendChild(buildAvatar(u.avatar, u.name, 'w-10 h-10'));
        const info = document.createElement('div');
        info.className = 'flex-1 min-w-0';
        const nm = document.createElement('p');
        nm.className = 'font-semibold text-brand-blue-light truncate';
        nm.textContent = u.name;
        const st = document.createElement('p');
        st.className = 'text-xs ' + (u.is_online ? 'text-emerald-700' : 'text-text-muted-light');
        st.textContent = u.is_online ? 'Online' : 'Offline';
        info.appendChild(nm);
        info.appendChild(st);
        btn.appendChild(info);
        userSearchResults.appendChild(btn);
      });
    }

    userSearchResults.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-user-id]');
      if (!btn) return;
      startConversationWith(parseInt(btn.dataset.userId, 10));
    });

    async function startConversationWith(userId) {
      try {
        const res = await fetch(ROUTES.openConversation, {
          method: 'POST',
          headers: jsonHeaders(),
          body: JSON.stringify({ user_id: userId }),
        });
        if (!res.ok) return;
        const data = await res.json();
        const conv = data.conversation;
        if (conv && !conversations.some((c) => c.id === conv.id)) {
          conversations.unshift(conv);
        }
        closeModal();
        renderConversations();
        openConversation(conv.id, true);
      } catch (e) { /* ignore */ }
    }

    userSearchInput.addEventListener('input', (e) => {
      clearTimeout(searchTimer);
      const q = e.target.value;
      searchTimer = setTimeout(() => searchUsers(q), 250);
    });

    document.getElementById('open-new-message').addEventListener('click', openModal);
    document.getElementById('new-message-inline').addEventListener('click', openModal);
    document.getElementById('empty-new-message').addEventListener('click', openModal);
    document.getElementById('close-new-message').addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

    // ---- Conversation search filter ----
    document.getElementById('conversation-search').addEventListener('input', (e) => {
      searchTerm = e.target.value;
      renderConversations();
    });

    // ---- Emoji picker ----
    const EMOJIS = ['😀','😁','😂','🤣','😊','😍','😘','😎','🤩','🥳','😇','🙂','😉','😌','😴','🤔','🙃','😅','😆','😜','🤗','🤝','👍','👎','👏','🙏','💪','🔥','✨','🎉','❤️','💙','💚','💛','💜','🧡','💯','✅','🎁','🌟','☀️','🌈','🍀','🐶','🐱','🍕','☕','🚀'];
    const emojiTrigger = document.getElementById('emoji-trigger');
    const emojiMenu = document.getElementById('emoji-menu');
    const emojiGrid = document.getElementById('emoji-grid');
    EMOJIS.forEach((emo) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'hover:bg-gray-100 rounded-md p-1';
      b.textContent = emo;
      b.addEventListener('click', () => {
        const start = inputEl.selectionStart ?? inputEl.value.length;
        const end = inputEl.selectionEnd ?? inputEl.value.length;
        inputEl.value = inputEl.value.slice(0, start) + emo + inputEl.value.slice(end);
        inputEl.focus();
        inputEl.selectionStart = inputEl.selectionEnd = start + emo.length;
        autoGrow();
      });
      emojiGrid.appendChild(b);
    });
    emojiTrigger.addEventListener('click', (e) => {
      e.stopPropagation();
      emojiMenu.classList.toggle('hidden');
    });
    document.addEventListener('click', (e) => {
      if (!emojiMenu.contains(e.target) && e.target !== emojiTrigger) emojiMenu.classList.add('hidden');
    });

    // ---- Init ----
    renderConversations();
    if (activeConversationId) {
      renderParticipant(bootstrap.participant);
      renderMessages(bootstrap.messages);
      chatEmptyEl.classList.add('hidden');
      setComposerEnabled(true);
    } else {
      messagesEl.classList.add('hidden');
      setComposerEnabled(false);
    }

    heartbeat();
    setInterval(heartbeat, 30000);
    setInterval(pollMessages, 3000);
    setInterval(pollThreads, 5000);
  })();
</script>
@endsection
