<template>
  <div class="content-section">
    <label class="section-label">Main Content</label>
    <QuillEditor
      v-model:content="mainContent"
      contentType="html"
      :placeholder="placeholder || 'Enter main content...'"
      :readOnly="disabled"
      :toolbar="toolbarOptions"
      :modules="modules"
      class="quill-editor-wrapper main-editor"
    />
  </div>
</template>

<script>
import { ref, watch, onMounted, nextTick } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

// Import Quill table module
import { TableModule } from 'quill-table-ui';
import 'quill-table-ui/dist/index.css';

export default {
  name: 'QuillEditorWithTables',
  components: {
    QuillEditor
  },
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: 'Enter main content...'
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const mainContent = ref(props.modelValue);

    // Enhanced toolbar with table functions
    const toolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      [{ color: [] }, { background: [] }],
      [{ font: [] }],
      [{ size: ['small', false, 'large', 'huge'] }],
      [{ list: 'ordered' }, { list: 'bullet' }],
      [{ script: 'sub' }, { script: 'super' }],
      [{ indent: '-1' }, { indent: '+1' }],
      [{ align: ['', 'center', 'right', 'justify'] }],
      ['code-block'],
      ['link', 'image'],
      // Table functionality
      [{
        'table': [
          'insert-table',
          'insert-row-above',
          'insert-row-below',
          'insert-column-left',
          'insert-column-right',
          'delete-row',
          'delete-column',
          'delete-table'
        ]
      }],
      ['clean'] // remove formatting button
    ];

    // Quill modules configuration with table support
    const modules = {
      table: TableModule,
      toolbar: {
        container: toolbarOptions,
        handlers: {
          'insert-table': function() {
            const tableModule = this.quill.getModule('table');
            tableModule.insertTable(3, 3);
          },
          'insert-row-above': function() {
            const tableModule = this.quill.getModule('table');
            tableModule.insertRowAbove();
          },
          'insert-row-below': function() {
            const tableModule = this.quill.getModule('table');
            tableModule.insertRowBelow();
          },
          'insert-column-left': function() {
            const tableModule = this.quill.getModule('table');
            tableModule.insertColumnLeft();
          },
          'insert-column-right': function() {
            const tableModule = this.quill.getModule('table');
            tableModule.insertColumnRight();
          },
          'delete-row': function() {
            const tableModule = this.quill.getModule('table');
            tableModule.deleteRow();
          },
          'delete-column': function() {
            const tableModule = this.quill.getModule('table');
            tableModule.deleteColumn();
          },
          'delete-table': function() {
            const tableModule = this.quill.getModule('table');
            tableModule.deleteTable();
          }
        }
      }
    };

    // Watch for changes and emit to parent
    watch(mainContent, (newValue) => {
      emit('update:modelValue', newValue);
    });

    // Watch for prop changes
    watch(() => props.modelValue, (newValue) => {
      if (newValue !== mainContent.value) {
        mainContent.value = newValue;
      }
    });

    return {
      mainContent,
      toolbarOptions,
      modules
    };
  }
};
</script>

<style scoped>
.content-section {
  margin-bottom: 1.5rem;
}

.section-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #374151;
  font-size: 0.875rem;
}

.quill-editor-wrapper {
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  overflow: hidden;
}

.main-editor {
  min-height: 200px;
}

/* Custom table styles */
:deep(.ql-table) {
  border-collapse: collapse;
  width: 100%;
}

:deep(.ql-table td) {
  border: 1px solid #e5e7eb;
  padding: 8px;
  vertical-align: top;
}

:deep(.ql-table th) {
  border: 1px solid #e5e7eb;
  padding: 8px;
  background-color: #f9fafb;
  font-weight: 600;
  vertical-align: top;
}

/* Table toolbar button styles */
:deep(.ql-toolbar .ql-table) {
  position: relative;
}

:deep(.ql-toolbar .ql-table .ql-picker-label) {
  padding: 2px 4px;
}

:deep(.ql-toolbar .ql-table .ql-picker-options) {
  padding: 4px 0;
  width: 200px;
}

:deep(.ql-toolbar .ql-table .ql-picker-item) {
  padding: 4px 8px;
  font-size: 13px;
}

:deep(.ql-toolbar .ql-table .ql-picker-item:hover) {
  background-color: #f3f4f6;
}

/* Custom table button icons */
:deep(.ql-table .ql-picker-label::before) {
  content: "Table";
  font-size: 12px;
}

/* Responsive table */
:deep(.ql-editor table) {
  width: 100%;
  max-width: 100%;
  overflow-x: auto;
  display: block;
  white-space: nowrap;
}

@media (max-width: 768px) {
  :deep(.ql-editor table) {
    font-size: 14px;
  }
  
  :deep(.ql-editor table td),
  :deep(.ql-editor table th) {
    padding: 6px;
    min-width: 100px;
  }
}
</style>