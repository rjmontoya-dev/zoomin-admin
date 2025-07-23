<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { nextTick, ref } from 'vue';

const recovery = ref(false);

const form = useForm({
    code: '',
    recovery_code: '',
});

const recoveryCodeInput = ref<HTMLInputElement | null>(null);
const codeInput = ref<HTMLInputElement | null>(null);

const toggleRecovery = async () => {
    recovery.value = !recovery.value;

    await nextTick();

    if (recovery.value) {
        recoveryCodeInput.value?.focus();
        form.code = '';
    } else {
        codeInput.value?.focus();
        form.recovery_code = '';
    }
};

const submit = () => {
    form.post(route('two-factor.login'));
};
</script>

<template>
    <AuthBase
        title="Two-Factor Challenge"
        description="Please enter your authentication code to log in."
    >
        <Head title="Two-Factor Challenge" />

        <div class="mb-4 text-sm text-muted-foreground">
            Please confirm access to your account by entering the authentication
            code provided by your authenticator application.
        </div>

        <form @submit.prevent="submit">
            <div v-if="!recovery">
                <Label for="code">Code</Label>
                <Input
                    ref="codeInput"
                    id="code"
                    type="text"
                    inputmode="numeric"
                    class="mt-1 block w-full"
                    v-model="form.code"
                    autofocus
                    autocomplete="one-time-code"
                />
                <InputError class="mt-2" :message="form.errors.code" />
            </div>

            <div v-if="recovery">
                <Label for="recovery_code">Recovery Code</Label>
                <Input
                    ref="recoveryCodeInput"
                    id="recovery_code"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.recovery_code"
                    autocomplete="one-time-code"
                />
                <InputError
                    class="mt-2"
                    :message="form.errors.recovery_code"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <button
                    type="button"
                    class="cursor-pointer text-sm text-muted-foreground hover:text-foreground"
                    @click.prevent="toggleRecovery"
                >
                    {{ recovery ? 'Use an authentication code' : 'Use a recovery code' }}
                </button>

                <Button
                    type="submit"
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    <LoaderCircle v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                    Log in
                </Button>
            </div>
        </form>
    </AuthBase>
</template>
