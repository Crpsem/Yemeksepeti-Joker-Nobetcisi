// (c) 2019 Crpsem Ltd. Şti. <"https://github.com/Crpsem">

document.addEventListener('DOMContentLoaded', function () {
  jokerNotifier.checkJoker();
});

// Her 2 dakikada bir kontrol eder
setInterval(function() {
  jokerNotifier.checkJoker(true);
}, 2 * 60 * 1000);
