<script>
var botmanWidget = {
    chatbot: "/botman{{ app()->getLocale() === 'en' ? '/en' : ''}}",
    title: "@lang('infos.surname') @lang('infos.name')",
    introMessage: "@lang('messages.intro', ['surname' => __('infos.surname')])",
//    bubbleAvatarUrl: "",
    bubbleBackground: "#5b7399",
    mainColor: "#5b7399",
    placeholderText: "@lang('messages.send-message')",
    aboutLink: "https://github.com/tteze/my-bot-agent",
    aboutText: "Github - My bot agent",
};
</script>
<script src="{{ asset('/js/botman.js') }}"></script>