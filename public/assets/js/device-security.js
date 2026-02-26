(function (window) {

    const DeviceSecurity = {

        async generateFingerprint() {
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
        },

        getOrCreateUUID() {
            let uuid = localStorage.getItem('device_uuid');

            if (!uuid) {
                uuid = crypto.randomUUID();
                localStorage.setItem('device_uuid', uuid);
            }

            return uuid;
        },

        async getGeo() {
            return new Promise((resolve) => {
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        resolve({
                            latitude: pos.coords.latitude.toFixed(6),
                            longitude: pos.coords.longitude.toFixed(6)
                        });
                    },
                    () => {
                        resolve({
                            latitude: 0,
                            longitude: 0
                        });
                    }
                );
            });
        },

        async collect() {
            const fingerprint = await this.generateFingerprint();
            const uuid = this.getOrCreateUUID();
            const geo = await this.getGeo();

            return {
                device_uuid: uuid,
                fingerprint_hash: fingerprint,
                latitude: geo.latitude,
                longitude: geo.longitude
            };
        },

        attachToForm(form) {

            form.addEventListener('submit', async function (e) {
                e.preventDefault();

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

                form.submit();
            });
        }

    };

    // Expose globally
    window.DeviceSecurity = DeviceSecurity;

})(window);