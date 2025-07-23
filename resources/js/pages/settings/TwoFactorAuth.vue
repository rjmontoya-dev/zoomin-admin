<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { Toaster, toast } from 'vue-sonner'
import LoadingPage from '@/components/shared/LoadingPage.vue';
import 'vue-sonner/style.css'
const breadcrumbItems = [
    {
        title: 'Two-Factor Authentication',
        href: '/settings/two-factor-authentication',
    },
];

const isLoading = ref(false);
const enabling = ref(false);
const disabling = ref(false);
const regenerating = ref(false);
const confirming = ref(false);

const twoFactorEnabled = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);
const recoveryCodesVisible = ref(false);
const confirmationCode = ref('');

const confirmingTwoFactorAuthentication = ref(false);

const statusHeading = computed(() => {
    if (twoFactorEnabled.value && !confirmingTwoFactorAuthentication.value) {
        return 'You have enabled two factor authentication.';
    }
    if (confirmingTwoFactorAuthentication.value) {
        return 'Finish enabling two factor authentication.';
    }
    return 'You have not enabled two factor authentication.';
});

const getTwoFactorStatus = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get('/user/two-factor-authentication');
        twoFactorEnabled.value = response.data.enabled;
        isLoading.value =  false
    } catch (error) {
        toast.error('Failed to fetch 2FA status.');
        isLoading.value =  false
    }
};

const enableTwoFactorAuthentication = async () => {
    enabling.value = true;
    try {
        const response = await axios.post('/user/two-factor-authentication');

        qrCode.value = response.data.svg;
        setupKey.value = response.data.secretKey;
        recoveryCodes.value = response.data.recoveryCodes;

        confirmingTwoFactorAuthentication.value = true;
        twoFactorEnabled.value = true;
        toast.success('2FA enabled successfully! Please confirm setup.');
    } catch (error) {
        toast.error('Failed to enable 2FA.');
    } finally {
        enabling.value = false;
    }
};


const confirmTwoFactorAuthentication = async () => {
    confirming.value = true;
    try {
        await axios.post('/user/confirmed-two-factor-authentication', {
            code: confirmationCode.value,
        });
        confirmingTwoFactorAuthentication.value = false;
        qrCode.value = null;
        setupKey.value = null;
        await showRecoveryCodes();
        toast.success('Two-factor authentication confirmed and enabled successfully.');
    } catch (error) {
        toast.error(error.response.data.message || 'Invalid code. Please try again.');
    } finally {
        confirming.value = false;
    }
};

const showRecoveryCodes = async () => {
    const response = await axios.get('/user/two-factor-recovery-codes');
    recoveryCodes.value = response.data;
};

const toggleRecoveryCodes = async () => {
    if (recoveryCodes.value.length === 0) {
        await showRecoveryCodes();
    }
    recoveryCodesVisible.value = !recoveryCodesVisible.value;
};

const regenerateRecoveryCodes = async () => {
    regenerating.value = true;
    try {
        await axios.post('/user/two-factor-recovery-codes');
        await showRecoveryCodes();
        toast.success('Recovery codes regenerated.');
    } catch (error) {
        toast.error('Failed to regenerate recovery codes.');
    } finally {
        regenerating.value = false;
    }
};

const disableTwoFactorAuthentication = async () => {
    disabling.value = true;
    try {
        await axios.delete('/user/two-factor-authentication');
        confirmingTwoFactorAuthentication.value = false;
        twoFactorEnabled.value = false;
        qrCode.value = null;
        setupKey.value = null;
        recoveryCodes.value = [];
        recoveryCodesVisible.value = false;
        toast.success('2FA disabled successfully!');
    } catch (error) {
        toast.error('Failed to disable 2FA.');
    } finally {
        disabling.value = false;
    }
};

onMounted(() => {
    getTwoFactorStatus();
});
</script>

<template>
    <Toaster :expand="true"  />
    <LoadingPage :isLoading="isLoading" />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Two-Factor Authentication" />

        <SettingsLayout>
            <Card>
                <CardHeader>
                    <CardTitle>Two Factor Authentication</CardTitle>
                    <CardDescription>
                        Add additional security to your account using two factor authentication.
                    </CardDescription>
                </CardHeader>

                <CardContent>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ statusHeading }}
                    </h3>

                    <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                        <p>
                            When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
                        </p>
                    </div>

                    <div v-if="twoFactorEnabled">
                        <div v-if="qrCode" class="mt-4">
                            <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                                <p v-if="confirmingTwoFactorAuthentication" class="font-semibold">
                                    Scan this QR code using Google Authenticator, Authy, or another authenticator app to complete setup.
                                </p>
                                <p v-else>
                                    Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application or enter the setup key.
                                </p>
                            </div>

                            <div class="mt-4 p-2 inline-block bg-white" v-html="qrCode" />

                            <div v-if="setupKey" class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                                <p class="font-semibold">
                                    Setup Key: <code class="font-mono">{{ setupKey }}</code>
                                </p>
                            </div>

                            <div v-if="confirmingTwoFactorAuthentication" class="mt-4">
                                <Input
                                    v-model="confirmationCode"
                                    type="text"
                                    placeholder="Enter OTP code"
                                    class="w-1/2"
                                    :disabled="confirming"
                                />
                                <Button @click="confirmTwoFactorAuthentication" :disabled="confirming" class="mt-2">
                                    <Loader2 v-if="confirming" class="mr-2 h-4 w-4 animate-spin" />
                                    Confirm
                                </Button>
                            </div>
                        </div>

                        <div v-if="recoveryCodesVisible" class="mt-4">
                            <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                                <p class="font-semibold">
                                    Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
                                </p>
                            </div>

                            <div class="grid gap-1 mt-4 px-4 py-4 font-mono text-sm bg-gray-100 dark:bg-gray-900 rounded-lg">
                                <div v-for="code in recoveryCodes" :key="code">
                                    {{ code }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 flex items-center gap-x-3">
                        <template v-if="!twoFactorEnabled">
                            <Button @click="enableTwoFactorAuthentication" :disabled="enabling">
                                <Loader2 v-if="enabling" class="mr-2 h-4 w-4 animate-spin" />
                                Enable
                            </Button>
                        </template>
                        <template v-else-if="twoFactorEnabled && !confirmingTwoFactorAuthentication">
                            <Button @click="toggleRecoveryCodes">
                                {{ recoveryCodesVisible ? 'Hide Recovery Codes' : 'Show Recovery Codes' }}
                            </Button>
                            <Button v-if="recoveryCodesVisible" @click="regenerateRecoveryCodes" :disabled="regenerating" variant="outline">
                                <Loader2 v-if="regenerating" class="mr-2 h-4 w-4 animate-spin" />
                                Regenerate Recovery Codes
                            </Button>
                            <Button @click="disableTwoFactorAuthentication" :disabled="disabling" variant="destructive">
                                <Loader2 v-if="disabling" class="mr-2 h-4 w-4 animate-spin" />
                                Disable
                            </Button>
                        </template>
                    </div>
                </CardContent>
            </Card>
        </SettingsLayout>
    </AppLayout>
</template>
