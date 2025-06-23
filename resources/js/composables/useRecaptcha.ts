import { useReCaptcha } from 'vue-recaptcha-v3';

export const useRecaptcha = () => {
    /**
     * Initializes a reCAPTCHA instance.
     * @returns {ReCaptchaInstance} An instance of the reCAPTCHA client.
     */
    const recaptchaInstance = useReCaptcha();

    /**
     * Executes reCAPTCHA verification and obtains a token.
     * @returns {Promise<string>} A Promise resolving to the reCAPTCHA token.
     */
    const recaptcha = async action => {
        await recaptchaInstance?.recaptchaLoaded();
        const token = await recaptchaInstance?.executeRecaptcha(action);
        const headerOptions = {
            headers: {
                'google-recaptcha-token': token,
            },
        };
        return { token, headerOptions };
    };

    return { recaptcha };
};
