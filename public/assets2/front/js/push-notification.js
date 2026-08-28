'use strict';

if (document.readyState === 'complete' || document.readyState === 'interactive') {
  initSW();
} else {
  window.addEventListener('DOMContentLoaded', initSW);
}

function initSW() {
  console.log("Push notifications: initSW called");

  if (!('serviceWorker' in navigator)) {
    console.warn("Push notifications: Service Worker is not supported in this browser.");
    return;
  }

  if (!('PushManager' in window)) {
    console.warn("Push notifications: PushManager is not supported in this browser.");
    return;
  }

  // Ensure VAPID keys are configured and available before running push registration
  if (typeof vapid_public_key === 'undefined' || !vapid_public_key) {
    console.warn("Push notifications: VAPID public key is not set or empty.");
    return;
  }

  // register the service worker
  navigator.serviceWorker.register('/sw.js')
    .then(() => {
      console.log("Push notifications: Service worker registered successfully.");
      initPush();
    })
    .catch((error) => {
      console.error("Push notifications: Service worker registration failed:", error);
    });
}

function initPush() {
  console.log("Push notifications: initPush called, current permission:", Notification.permission);
  if (!navigator.serviceWorker.ready) {
    console.warn("Push notifications: Service Worker is not ready.");
    return;
  }

  if (Notification.permission === 'denied') {
    console.warn("Push notifications: Permission is denied. Prompt will not show.");
    return;
  }

  new Promise(function (resolve, reject) {
    const permissionResult = Notification.requestPermission().then(function (result) {
      resolve(result);
    });

    if (permissionResult) {
      permissionResult.then(resolve, reject);
    }
  }).then((permissionResult) => {
    console.log("Push notifications: User selection:", permissionResult);
    if (permissionResult !== 'granted') {
      return;
    }

    subscribeGuest();
  });
}

function subscribeGuest() {
  console.log("Push notifications: Subscribing user...");
  navigator.serviceWorker.ready
    .then((registration) => {
      const subscribeOptions = {
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(vapid_public_key)
      };

      return registration.pushManager.getSubscription()
        .then((existingSubscription) => {
          if (existingSubscription) {
            console.log("Push notifications: Existing subscription found, verifying VAPID keys...");
            var currentKey = urlBase64ToUint8Array(vapid_public_key);
            var existingKey = new Uint8Array(existingSubscription.options.applicationServerKey);
            var keysMatch = true;
            if (currentKey.length !== existingKey.length) {
              keysMatch = false;
            } else {
              for (var i = 0; i < currentKey.length; i++) {
                if (currentKey[i] !== existingKey[i]) {
                  keysMatch = false;
                  break;
                }
              }
            }

            if (!keysMatch) {
              console.log("Push notifications: VAPID keys changed, unsubscribing existing subscription...");
              return existingSubscription.unsubscribe().then(() => {
                console.log("Push notifications: Unsubscribed old subscription successfully. Resubscribing...");
                return registration.pushManager.subscribe(subscribeOptions);
              });
            }

            console.log("Push notifications: Existing subscription VAPID keys match current keys.");
            return existingSubscription;
          }

          console.log("Push notifications: No existing subscription found. Subscribing...");
          return registration.pushManager.subscribe(subscribeOptions);
        })
        .catch((error) => {
          console.warn("Push notifications: Subscription check failed, attempting auto-unsubscribe recovery...", error);
          return registration.pushManager.getSubscription().then((existingSubscription) => {
            if (existingSubscription) {
              return existingSubscription.unsubscribe().then(() => {
                console.log("Push notifications: Recovered by unsubscribing. Subscribing with new key...");
                return registration.pushManager.subscribe(subscribeOptions);
              });
            }
            throw error;
          });
        });
    })
    .then((pushSubscription) => {
      console.log("Push notifications: Subscription object generated successfully:", pushSubscription);
      storePushSubscription(pushSubscription);
    })
    .catch(error => {
      console.error("Push notifications: Subscription failed:", error);
    });
}

function urlBase64ToUint8Array(base64String) {
  var padding = '='.repeat((4 - base64String.length % 4) % 4);

  var base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');

  var rawData = window.atob(base64);
  var outputArray = new Uint8Array(rawData.length);

  for (var i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }

  return outputArray;
}

function storePushSubscription(pushSubscription) {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  fetch(mainurl + '/push', {
    method: 'POST',
    body: JSON.stringify(pushSubscription),
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-Token': token
    }
  }).then((response) => {
    return response.json();
  }).then((data) => {
    console.log("Push notifications: Subscription stored successfully in database:", data);
  }).catch((error) => {
    console.error("Push notifications: Failed to store subscription in database:", error);
  });
}
