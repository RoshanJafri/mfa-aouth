(function (window) {

    const DeviceSecurity = {

        _cachedData: null,
        _collecting: null,

        async generateFingerprint() {
            try {
                const userAgent = navigator.userAgent;
                const screenRes = `${screen.width}x${screen.height}`;
                const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                const language = navigator.language;

                const raw = `${userAgent}|${screenRes}|${timezone}|${language}`;

                const buffer = await crypto.subtle.digest(
                    'SHA-256',
                    new TextEncoder().encode(raw)
                );

                return Array.from(new Uint8Array(buffer))
                    .map(b => b.toString(16).padStart(2, '0'))
                    .join('');
            } catch (e) {
                console.warn('Fingerprint failed', e);
                return 'unavailable';
            }
        },

        getOrCreateUUID() {
            try {
                let uuid = localStorage.getItem('device_uuid');

                if (!uuid) {
                    uuid = crypto.randomUUID();
                    localStorage.setItem('device_uuid', uuid);
                }

                return uuid;
            } catch (e) {
                return 'unavailable';
            }
        },

        async getGeo() {
            return new Promise((resolve) => {

                if (!navigator.geolocation) {
                    return resolve({ latitude: 0, longitude: 0 });
                }

                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        resolve({
                            latitude: pos.coords.latitude.toFixed(6),
                            longitude: pos.coords.longitude.toFixed(6)
                        });
                    },
                    () => {
                        resolve({ latitude: 0, longitude: 0 });
                    },
                    {
                        enableHighAccuracy: false,
                        timeout: 3000,
                        maximumAge: 60000
                    }
                );
            });
        },

        async collect() {

            if (this._cachedData) {
                return this._cachedData;
            }

            if (this._collecting) {
                return this._collecting;
            }

            this._collecting = (async () => {
                const fingerprint = await this.generateFingerprint();
                const uuid = this.getOrCreateUUID();
                const geo = await this.getGeo();

                const data = {
                    device_uuid: uuid,
                    fingerprint_hash: fingerprint,
                    latitude: geo.latitude,
                    longitude: geo.longitude
                };

                this._cachedData = data;
                return data;
            })();

            return this._collecting;
        },

        attachToForm(form) {

            if (!form) return;

            let submitting = false;

            form.addEventListener('submit', async function (e) {

                if (submitting) return;

                e.preventDefault();
                submitting = true;

                try {
                    const securityData = await DeviceSecurity.collect();

                    for (const key in securityData) {
                        let input = form.querySelector(`input[name="${key}"]`);

                        if (!input) {
                            input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            form.appendChild(input);
                        }

                        input.value = securityData[key];
                    }

                } catch (err) {
                    console.error('DeviceSecurity error:', err);
                }

                form.submit();
            });
        }
    };

    window.DeviceSecurity = DeviceSecurity;

})(window);