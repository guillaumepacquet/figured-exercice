<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    price: Number
});

const form = useForm({
    quantity: 0,
});

const submit = () => {
    form.get(route('price.get'));
};
</script>

<template>
    <GuestLayout>
        <Head title="home" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="quantity" value="Quantity of product" />

                <TextInput
                    id="quantity"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.quantity"
                    required
                    autofocus
                />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Request Price
                </PrimaryButton>
            </div>
        </form>

        <div v-if="price > 0">
            Your price is: {{price}}$
        </div>
        <div v-else class="text-red-500 mt-2">
            Sorry, the quantity applied exceeds the quantity on hand.
        </div>
    </GuestLayout>
</template>
