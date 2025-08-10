
  function generateFingerprint() {
    const ua = navigator.userAgent;
    const platform = navigator.platform;
    const timezone = new Date().getTimezoneOffset();
    const data = ua + '|' + platform + '|' + timezone;
    

    let hash = 0;
    for (let i = 0; i < data.length; i++) {
      hash = ((hash << 5) - hash) + data.charCodeAt(i);
      hash |= 0;
    }
    return 'fp_' + Math.abs(hash);
  }

  function getDeviceInfo() {
    return {
      id: generateFingerprint(),
      userAgent: navigator.userAgent,
      platform: navigator.platform,
      language: navigator.language,
      screenWidth: screen.width,
      screenHeight: screen.height,
      timezoneOffset: new Date().getTimezoneOffset()
    };
  }


  document.addEventListener('DOMContentLoaded', () => {
    const fingerprintInput = document.getElementById('device_fingerprint');
    const deviceInfo = getDeviceInfo();
    fingerprintInput.value = JSON.stringify(deviceInfo);
  });