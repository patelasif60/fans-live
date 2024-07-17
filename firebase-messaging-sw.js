importScripts('https://www.gstatic.com/firebasejs/7.24.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.24.0/firebase-messaging.js');

var config = {
    apiKey: "AIzaSyBegW3VS_9B6R2nvzrgGH7fEWPTBF4FjUc",
    authDomain: "test-web-app-notification.firebaseapp.com",
    databaseURL: "https://test-web-app-notification.firebaseio.com",
    projectId: "test-web-app-notification",
    storageBucket: "test-web-app-notification.appspot.com",
    messagingSenderId: "570919809220",
    appId: "1:570919809220:web:09a83f901a49340ce49991",
    measurementId: "G-70TEJ78CE6"
};
firebase.initializeApp(config);

var messaging = firebase.messaging();

// messaging.onMessage((payload) => {
//   console.log('Message received. ', payload);
// });

messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = 'Background Message Title';
  const notificationOptions = {
    body: 'Background Message body.',
    icon: '/firebase-logo.png'
  };

  // self.registration.showNotification(notificationTitle,
  //   notificationOptions);
});