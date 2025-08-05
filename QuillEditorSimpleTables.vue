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
      @ready="onEditorReady"
    />
  </div>
</template>

<script>
import { ref, watch, onMounted, nextTick } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

// Import Quill better table module
import QuillBetterTable from 'quill-better-table';
import 'quill-better-table/dist/quill-better-table.css';

export default {
  name: 'QuillEditorSimpleTables',
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
    const quillInstance = ref(null);

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
      ['insertTable', 'insertRowAbove', 'insertRowBelow', 'insertColumnLeft', 'insertColumnRight'],
      ['deleteRow', 'deleteColumn', 'deleteTable'],
      ['clean'] // remove formatting button
    ];

    // Quill modules configuration with better table support
    const modules = {
      'better-table': {
        operationMenu: {
          items: {
            unmergeCells: {
              text: 'Another unmerge cells name'
            }
          },
          color: {
            colors: ['green', 'red', 'yellow', 'blue', 'white'],
            text: 'Background Colors:'
          }
        }
      },
      keyboard: {
        bindings: QuillBetterTable.keyboardBindings
      },
      toolbar: {
        container: toolbarOptions,
        handlers: {
          'insertTable': function() {
            const tableModule = this.quill.getModule('better-table');
            tableModule.insertTable(3, 3);
          },
          'insertRowAbove': function() {
            const tableModule = this.quill.getModule('better-table');
            tableModule.insertRowAbove();
          },
          'insertRowBelow': function() {
            const tableModule = this.quill.getModule('better-table');
            tableModule.insertRowBelow();
          },
          'insertColumnLeft': function() {
            const tableModule = this.quill.getModule('better-table');
            tableModule.insertColumnLeft();
          },
          'insertColumnRight': function() {
            const tableModule = this.quill.getModule('better-table');
            tableModule.insertColumnRight();
          },
          'deleteRow': function() {
            const tableModule = this.quill.getModule('better-table');
            tableModule.deleteRow();
          },
          'deleteColumn': function() {
            const tableModule = this.quill.getModule('better-table');
            tableModule.deleteColumn();
          },
          'deleteTable': function() {
            const tableModule = this.quill.getModule('better-table');
            tableModule.deleteTable();
          }
        }
      }
    };

    // Handle editor ready event
    const onEditorReady = (quill) => {
      quillInstance.value = quill;
      
      // Register the better-table module
      quill.getModule('better-table');
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
      modules,
      onEditorReady
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

/* Better Table Styles */
:deep(.ql-better-table) {
  border-collapse: collapse;
  width: 100%;
}

:deep(.ql-better-table td) {
  border: 1px solid #e5e7eb;
  padding: 8px;
  vertical-align: top;
  position: relative;
}

:deep(.ql-better-table th) {
  border: 1px solid #e5e7eb;
  padding: 8px;
  background-color: #f9fafb;
  font-weight: 600;
  vertical-align: top;
}

/* Table operation menu */
:deep(.qlbt-operation-menu) {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  z-index: 100;
}

:deep(.qlbt-operation-menu-item) {
  padding: 8px 12px;
  cursor: pointer;
  font-size: 13px;
}

:deep(.qlbt-operation-menu-item:hover) {
  background-color: #f3f4f6;
}

/* Toolbar button styles */
:deep(.ql-toolbar button) {
  padding: 2px 4px;
  margin: 1px;
}

:deep(.ql-toolbar .ql-insertTable) {
  width: auto;
}

:deep(.ql-toolbar .ql-insertTable::after) {
  content: "Table";
  font-size: 11px;
}

:deep(.ql-toolbar .ql-insertRowAbove::after) {
  content: "Row↑";
  font-size: 10px;
}

:deep(.ql-toolbar .ql-insertRowBelow::after) {
  content: "Row↓";
  font-size: 10px;
}

:deep(.ql-toolbar .ql-insertColumnLeft::after) {
  content: "Col←";
  font-size: 10px;
}

:deep(.ql-toolbar .ql-insertColumnRight::after) {
  content: "Col→";
  font-size: 10px;
}

:deep(.ql-toolbar .ql-deleteRow::after) {
  content: "Del Row";
  font-size: 9px;
}

:deep(.ql-toolbar .ql-deleteColumn::after) {
  content: "Del Col";
  font-size: 9px;
}

:deep(.ql-toolbar .ql-deleteTable::after) {
  content: "Del Table";
  font-size: 9px;
}

/* Responsive design */
@media (max-width: 768px) {
  :deep(.ql-toolbar) {
    flex-wrap: wrap;
  }
  
  :deep(.ql-better-table) {
    font-size: 14px;
  }
  
  :deep(.ql-better-table td),
  :deep(.ql-better-table th) {
    padding: 6px;
    min-width: 80px;
  }
  
  :deep(.ql-toolbar button) {
    padding: 1px 2px;
    margin: 0.5px;
  }
}
</style>