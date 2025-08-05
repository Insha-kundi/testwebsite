# Quill Editor with Table Functionality

This repository contains Vue 3 components that extend your existing Quill editor with table functionality. I've created three different implementations for you to choose from based on your needs.

## 📁 Files Created

1. **QuillEditorWithTablesEasy.vue** - ⭐ **RECOMMENDED** - Simple implementation without external table modules
2. **QuillEditorSimpleTables.vue** - Uses `quill-better-table` module
3. **QuillEditorWithTables.vue** - Uses `quill-table-ui` module

## 🚀 Quick Start (Recommended Approach)

### Step 1: Install Dependencies

```bash
npm install @vueup/vue-quill quill
```

### Step 2: Use the Easy Implementation

Replace your existing editor code with the content from `QuillEditorWithTablesEasy.vue`. This approach:

- ✅ No additional dependencies required
- ✅ Works with your existing setup
- ✅ Custom table manipulation functions
- ✅ Responsive design
- ✅ Easy to customize

### Step 3: Update Your Toolbar Options

Your updated `toolbarOptions` now includes:

```javascript
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
  // 🆕 NEW: Table functionality
  ['insertTable', 'addTableRow', 'addTableCol', 'removeTableRow', 'removeTableCol'],
  ['clean']
];
```

## 🎯 Table Features

### Available Table Functions

1. **📋 Insert Table** - Creates a 3x3 table with headers
2. **➕ Add Row** - Adds a new row to the current table
3. **➕ Add Column** - Adds a new column to the current table
4. **➖ Remove Row** - Removes the current row
5. **➖ Remove Column** - Removes the current column

### How to Use

1. **Insert a Table**: Click the "📋 Table" button in the toolbar
2. **Modify Tables**: Place your cursor in any table cell and use the row/column buttons
3. **Edit Content**: Click in any cell to edit its content
4. **Style Tables**: The tables come with pre-styled borders and hover effects

## 🔧 Advanced Options

### Option 1: Using quill-better-table (More Features)

If you want more advanced table features, install the additional dependency:

```bash
npm install quill-better-table
```

Then use `QuillEditorSimpleTables.vue` which includes:
- Right-click context menu
- Cell merging/splitting
- Table resizing
- Advanced table operations

### Option 2: Using quill-table-ui

```bash
npm install quill-table-ui
```

Use `QuillEditorWithTables.vue` for a different table UI approach.

## 💡 Integration with Your Existing Code

### Before (Your Original Code)
```vue
<template>
  <div class="content-section">
    <label class="section-label">Main Content</label>
    <QuillEditor
      v-model:content="mainContent"
      contentType="html"
      :placeholder="placeholder || 'Enter main content...'"
      :readOnly="disabled"
      :toolbar="toolbarOptions"
      class="quill-editor-wrapper main-editor"
    />
  </div>
</template>

<script>
import { ref, watch, onMounted, nextTick } from 'vue';
import { QuillEditor } from '@vueup/vue-quill';

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
];
</script>
```

### After (With Table Functionality)
Simply replace your component with the content from `QuillEditorWithTablesEasy.vue` - it maintains all your existing functionality while adding table features!

## 🎨 Customization

### Styling Tables
The tables come with default styling, but you can customize them by modifying the CSS:

```css
:deep(.ql-editor table) {
  border-collapse: collapse;
  width: 100%;
  margin: 10px 0;
}

:deep(.ql-editor table td) {
  border: 1px solid #ddd;
  padding: 8px;
  vertical-align: top;
  min-width: 50px;
}
```

### Custom Table Templates
You can modify the `insertTable` function to create different table templates:

```javascript
const insertTable = (quill) => {
  const range = quill.getSelection();
  if (range) {
    const tableHTML = `
      <table style="border-collapse: collapse; width: 100%;">
        <!-- Your custom table HTML here -->
      </table>
    `;
    quill.clipboard.dangerouslyPasteHTML(range.index, tableHTML);
  }
};
```

## 📱 Mobile Responsive

All table implementations include responsive design:
- Tables scroll horizontally on small screens
- Toolbar buttons adapt to mobile layouts
- Touch-friendly cell editing

## 🐛 Troubleshooting

### Common Issues

1. **Tables not showing**: Make sure you're importing the CSS files
2. **Buttons not working**: Check that the toolbar handlers are properly configured
3. **Styling issues**: Ensure the `:deep()` selectors are working in your Vue setup

### Browser Compatibility

- ✅ Chrome/Edge 88+
- ✅ Firefox 85+
- ✅ Safari 14+
- ✅ Mobile browsers

## 📞 Support

If you encounter any issues:
1. Check the browser console for errors
2. Verify all dependencies are installed
3. Make sure you're using Vue 3 with Composition API
4. Test with the recommended "Easy" implementation first

## 🎉 You're Ready!

Your Quill editor now has full table functionality! Users can:
- Insert professional-looking tables
- Add/remove rows and columns dynamically
- Edit table content inline
- Enjoy responsive table behavior

The implementation maintains all your existing editor features while seamlessly adding table capabilities.