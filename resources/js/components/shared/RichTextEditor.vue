<template>
    <div>
      <textarea ref="editor"></textarea>
    </div>
</template>

<script setup>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { ref, shallowRef, onMounted, onBeforeUnmount, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    }
});

const emit = defineEmits(['update:modelValue']);

const editor = ref(null);
const editorInstance = shallowRef(null); // Use shallowRef to prevent deep reactivity

onMounted(() => {
    ClassicEditor.create(editor.value)
        .then(editorObj => {
            editorInstance.value = editorObj;

            const initialData = props.modelValue || '';
            editorObj.setData(initialData);

            editorObj.model.document.on('change:data', () => {
                const editorData = editorObj.getData();
                emit('update:modelValue', editorData);
            });
        })
        .catch(error => {
            console.error('There was a problem initializing the editor:', error);
        });
});

onBeforeUnmount(() => {
    if (editorInstance.value) {
        editorInstance.value.destroy();
        editorInstance.value = null;
    }
});

watch(() => props.modelValue, (newValue) => {
    if (editorInstance.value && newValue !== editorInstance.value.getData()) {
        editorInstance.value.setData(newValue || '');
    }
});
</script>
<style>
/* Add any necessary styles for the editor */
.ck-editor__editable {
    min-height: 200px;
}
</style>
