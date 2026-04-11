@extends('layouts.app', ['headerPartial' => 'partials.header-auth'])

@section('title', 'Simply Wishes - Forum Contribute')

@section('content')
<main class="flex-grow">
      <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="text-center mb-8 md:mb-12">
          <h1 class="text-4xl md:text-5xl font-bold text-slate-800 dark:text-white mb-2">Community Forum</h1>
          <p class="text-lg text-slate-600 dark:text-slate-400">Share stories, ask questions, and connect with our community.</p>
        </div>
        <div class="mb-8 md:mb-10">
          <div class="relative max-w-2xl mx-auto">
            <input class="w-full pl-12 pr-4 py-3 rounded-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-primary focus:border-primary transition" placeholder="Search articles, videos, and more..." type="search" />
            <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500">search</span>
          </div>
        </div>
        <div class="flex justify-center border-b border-slate-200 dark:border-slate-700 mb-8 md:mb-10">
          <div class="flex space-x-4 sm:space-x-8">
            <button class="py-3 px-2 text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors font-medium text-sm sm:text-base">
              <span class="material-icons align-middle mr-1 text-lg">article</span>
              Articles
            </button>
            <button class="py-3 px-2 text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors font-medium text-sm sm:text-base">
              <span class="material-icons align-middle mr-1 text-lg">videocam</span>
              Videos
            </button>
            <button class="py-3 px-2 text-primary border-b-2 border-primary font-semibold text-sm sm:text-base">
              <span class="material-icons align-middle mr-1 text-lg">volunteer_activism</span>
              Contribute
            </button>
          </div>
        </div>
        <div x-data="{ tab: 'article' }">
          <div class="max-w-4xl mx-auto">
            <div class="flex justify-center mb-8">
              <div class="flex rounded-lg p-1 bg-slate-100 dark:bg-slate-800 space-x-1">
                <button :class="{'bg-white dark:bg-primary text-primary dark:text-white shadow': tab === 'article', 'text-slate-600 dark:text-slate-300': tab !== 'article'}" @click="tab = 'article'" class="px-6 py-2 rounded-md font-semibold transition-colors duration-300">
                  Contribute Article
                </button>
                <button :class="{'bg-white dark:bg-primary text-primary dark:text-white shadow': tab === 'video', 'text-slate-600 dark:text-slate-300': tab !== 'video'}" @click="tab = 'video'" class="px-6 py-2 rounded-md font-semibold transition-colors duration-300">
                  Contribute Video
                </button>
              </div>
            </div>
            <div x-show="tab === 'article'">
              <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 md:p-8">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Article</h2>
                <p class="text-slate-600 dark:text-slate-400 mb-6">We invite you to post an article about a topic of general interest, and let your voice be heard.</p>
                <form class="space-y-6">
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-title">Title <span class="text-red-500">*</span></label>
                    <input class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="article-title" type="text" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-content">Write or Insert your article in the space provided below <span class="text-red-500">*</span></label>
                    <div class="rounded-md border border-slate-300 dark:border-slate-600">
                      <div class="flex items-center p-2 border-b border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 space-x-1 text-slate-600 dark:text-slate-300">
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">undo</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">redo</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_bold</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_italic</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_underlined</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">strikethrough_s</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">link</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_quote</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">image</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">help_outline</span></button>
                      </div>
                      <textarea class="w-full h-48 p-3 bg-transparent border-0 focus:ring-0 resize-y dark:text-white" id="article-content"></textarea>
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="article-image">Upload an Image for your Article <span class="text-red-500">*</span></label>
                    <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-primary/20 dark:file:text-white hover:file:bg-primary/20" id="article-image" type="file" />
                  </div>
                  <div class="flex justify-end">
                    <button class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-primary/90 transition-colors" type="submit">Create</button>
                  </div>
                </form>
              </div>
            </div>
            <div x-show="tab === 'video'" style="display: none;">
              <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md p-6 md:p-8">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Video</h2>
                <p class="text-slate-600 dark:text-slate-400 mb-6">We invite you to post a video, about a topic of general interest, and let your voice be heard.</p>
                <form class="space-y-6">
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="video-title">Title <span class="text-red-500">*</span></label>
                    <input class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="video-title" type="text" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="video-description">Description</label>
                    <div class="rounded-md border border-slate-300 dark:border-slate-600">
                      <div class="flex items-center p-2 border-b border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 space-x-1 text-slate-600 dark:text-slate-300">
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">undo</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">redo</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_bold</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_italic</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_underlined</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">strikethrough_s</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">link</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">format_quote</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">image</span></button>
                        <button class="p-1.5 rounded hover:bg-slate-200 dark:hover:bg-slate-600" type="button"><span class="material-icons text-base">help_outline</span></button>
                      </div>
                      <textarea class="w-full h-32 p-3 bg-transparent border-0 focus:ring-0 resize-y dark:text-white" id="video-description"></textarea>
                    </div>
                  </div>
                  <div>
                    <p class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Upload a Video <span class="text-red-500">*</span></p>
                    <div class="space-y-4">
                      <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="youtube-url">Insert youtube video url in order to upload</label>
                        <input class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-slate-700 dark:border-slate-600 dark:text-white" id="youtube-url" placeholder="http:// or https://" type="url" />
                      </div>
                      <div class="text-center text-slate-500 dark:text-slate-400">( Or )</div>
                      <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="video-file">Upload from your Files</label>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mb-2">Choose a video that you own. Do not upload any copyright images such as images from movie characters or known company products or your post could be deleted without warning.</p>
                        <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-primary/20 dark:file:text-white hover:file:bg-primary/20" id="video-file" type="file" />
                      </div>
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" for="thumbnail-image">Upload a Thumbnail Image for your Video <span class="text-red-500">*</span></label>
                    <input class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-primary/20 dark:file:text-white hover:file:bg-primary/20" id="thumbnail-image" type="file" />
                  </div>
                  <div class="flex justify-end">
                    <button class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-primary/90 transition-colors" type="submit">Create</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection
