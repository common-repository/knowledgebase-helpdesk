/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/*!***********************!*\
  !*** ./src/blocks.js ***!
  \***********************/
/*! no exports provided */
/*! all exports used */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("Object.defineProperty(__webpack_exports__, \"__esModule\", { value: true });\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__block_block_js__ = __webpack_require__(/*! ./block/block.js */ 1);\n/**\n * Gutenberg Blocks\n *\n * All blocks related JavaScript files should be imported here.\n * You can create a new block folder in this dir and include code\n * for that block here as well.\n *\n * All blocks should be included here since this is the file that\n * Webpack is compiling as the input file.\n */\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMC5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9ja3MuanM/N2I1YiJdLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEd1dGVuYmVyZyBCbG9ja3NcbiAqXG4gKiBBbGwgYmxvY2tzIHJlbGF0ZWQgSmF2YVNjcmlwdCBmaWxlcyBzaG91bGQgYmUgaW1wb3J0ZWQgaGVyZS5cbiAqIFlvdSBjYW4gY3JlYXRlIGEgbmV3IGJsb2NrIGZvbGRlciBpbiB0aGlzIGRpciBhbmQgaW5jbHVkZSBjb2RlXG4gKiBmb3IgdGhhdCBibG9jayBoZXJlIGFzIHdlbGwuXG4gKlxuICogQWxsIGJsb2NrcyBzaG91bGQgYmUgaW5jbHVkZWQgaGVyZSBzaW5jZSB0aGlzIGlzIHRoZSBmaWxlIHRoYXRcbiAqIFdlYnBhY2sgaXMgY29tcGlsaW5nIGFzIHRoZSBpbnB1dCBmaWxlLlxuICovXG5cbmltcG9ydCAnLi9ibG9jay9ibG9jay5qcyc7XG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvYmxvY2tzLmpzXG4vLyBtb2R1bGUgaWQgPSAwXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Iiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///0\n");

/***/ }),
/* 1 */
/*!****************************!*\
  !*** ./src/block/block.js ***!
  \****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss__ = __webpack_require__(/*! ./style.scss */ 2);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__style_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__style_scss__);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss__ = __webpack_require__(/*! ./editor.scss */ 3);\n/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__editor_scss___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__editor_scss__);\n/**\n * BLOCK: knowledgebase-blocks\n *\n * Registering a basic block with Gutenberg.\n * Simple block, renders and saves the same content without any interactivity.\n */\n\n//  Import CSS.\n\n\n\nvar __ = wp.i18n.__; // Import __() from wp.i18n\n\nvar registerBlockType = wp.blocks.registerBlockType; // Import registerBlockType() from wp.blocks\n\n/**\n * Register: aa Gutenberg Block.\n *\n * Registers a new block provided a unique name and an object defining its\n * behavior. Once registered, the block is made editor as an option to any\n * editor interface where blocks are implemented.\n *\n * @link https://wordpress.org/gutenberg/handbook/block-api/\n * @param  {string}   name     Block name.\n * @param  {Object}   settings Block settings.\n * @return {?WPBlock}          The block, if it has been successfully\n *                             registered; otherwise `undefined`.\n */\n\nregisterBlockType('kbx/block-knowledgebase-blocks', {\n\t// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.\n\ttitle: __('Knowledgebase Helpdesk — Shortcode Generator'), // Block title.\n\ticon: 'shield', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.\n\tcategory: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.\n\tkeywords: [__('Knowledgebase Blocks'), __('Knowledgebase Helpdesk'), __('Knowledgebase Helpdesk - Shortcode Generator')],\n\tattributes: {\n\t\tshortcode: {\n\t\t\ttype: 'string',\n\t\t\tdefault: ''\n\t\t}\n\t},\n\n\t/**\n  * The edit function describes the structure of your block in the context of the editor.\n  * This represents what the editor will render when the block is used.\n  *\n  * The \"edit\" property must be a valid function.\n  *\n  * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/\n  */\n\tedit: function edit(props) {\n\t\tvar shortcode = props.attributes.shortcode,\n\t\t    setAttributes = props.setAttributes;\n\n\n\t\tfunction showShortcodeModal(e) {\n\t\t\tjQuery('#kbx_shortcode_generator_meta_block').prop('disabled', true);\n\t\t\tjQuery(e.target).addClass('currently_editing');\n\t\t\tjQuery.post(ajaxurl, {\n\t\t\t\taction: 'show_kbx_shortcode_cmn'\n\n\t\t\t}, function (data) {\n\t\t\t\tjQuery('#wpwrap').append(data);\n\t\t\t});\n\t\t}\n\n\t\tfunction insertShortCode(e) {\n\t\t\tvar shortcodeData = jQuery('#kbx_add_shortcode_cmn').attr('gutenberg_kbx_shortcode_generator_value');\n\t\t\tsetAttributes({ shortcode: shortcodeData });\n\t\t\tjQuery('#kbx_add_shortcode_cmn').parents('#sm-modal').remove();\n\t\t\tjQuery('.currently_editing').removeClass('currently_editing');\n\t\t\tjQuery('#kbx_shortcode_generator_meta_block').removeAttr('disabled');\n\t\t\tconsole.log({ shortcode: shortcode });\n\t\t}\n\n\t\tjQuery(document).on('click', '#kbx_add_shortcode_cmn', function () {\n\t\t\t//e.preventDefault();\n\t\t\tjQuery('.currently_editing').next('#insert_kbx_shortcode').trigger('click');\n\t\t});\n\n\t\tfunction getShortCode() {\n\t\t\tjQuery(document).find('#kbx_add_shortcode_cmn').trigger('click');\n\t\t\t//jQuery(document).find( '.modal-content .close').trigger('click');\n\t\t}\n\t\tjQuery(document).on('click', '.modal-content .close', function () {\n\t\t\tjQuery('.currently_editing').removeClass('currently_editing');\n\t\t\tjQuery('#kbx_shortcode_generator_meta_block').removeAttr('disabled');\n\t\t});\n\n\t\treturn wp.element.createElement(\n\t\t\t'div',\n\t\t\t{ className: props.className },\n\t\t\twp.element.createElement('input', { type: 'button', id: 'kbx_shortcode_generator_meta_block', onClick: showShortcodeModal, className: 'button button-primary button-large', value: 'Generate Knowledgebase Shortcode' }),\n\t\t\twp.element.createElement('input', { type: 'button', id: 'insert_kbx_shortcode', onClick: insertShortCode, className: 'button button-primary button-large gutenberg_hidden', value: 'Test Knowledgebase Shortcode' }),\n\t\t\twp.element.createElement('input', { type: 'button', id: 'get_kbx_shortcode', onClick: getShortCode, className: 'button button-primary button-large gutenberg_hidden', value: 'Get Knowledgebase Shortcode' }),\n\t\t\twp.element.createElement('br', null),\n\t\t\twp.element.createElement(\n\t\t\t\t'div',\n\t\t\t\t{ className: 'kbx_shortcode_value' },\n\t\t\t\tshortcode\n\t\t\t)\n\t\t);\n\t},\n\n\t/**\n  * The save function defines the way in which the different attributes should be combined\n  * into the final markup, which is then serialized by Gutenberg into post_content.\n  *\n  * The \"save\" property must be specified and must be a valid function.\n  *\n  * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/\n  */\n\tsave: function save(props) {\n\t\tvar shortcode = props.attributes.shortcode;\n\n\t\treturn wp.element.createElement(\n\t\t\t'div',\n\t\t\tnull,\n\t\t\tshortcode\n\t\t);\n\t}\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMS5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9jay9ibG9jay5qcz85MjFkIl0sInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogQkxPQ0s6IGtub3dsZWRnZWJhc2UtYmxvY2tzXG4gKlxuICogUmVnaXN0ZXJpbmcgYSBiYXNpYyBibG9jayB3aXRoIEd1dGVuYmVyZy5cbiAqIFNpbXBsZSBibG9jaywgcmVuZGVycyBhbmQgc2F2ZXMgdGhlIHNhbWUgY29udGVudCB3aXRob3V0IGFueSBpbnRlcmFjdGl2aXR5LlxuICovXG5cbi8vICBJbXBvcnQgQ1NTLlxuaW1wb3J0ICcuL3N0eWxlLnNjc3MnO1xuaW1wb3J0ICcuL2VkaXRvci5zY3NzJztcblxudmFyIF9fID0gd3AuaTE4bi5fXzsgLy8gSW1wb3J0IF9fKCkgZnJvbSB3cC5pMThuXG5cbnZhciByZWdpc3RlckJsb2NrVHlwZSA9IHdwLmJsb2Nrcy5yZWdpc3RlckJsb2NrVHlwZTsgLy8gSW1wb3J0IHJlZ2lzdGVyQmxvY2tUeXBlKCkgZnJvbSB3cC5ibG9ja3NcblxuLyoqXG4gKiBSZWdpc3RlcjogYWEgR3V0ZW5iZXJnIEJsb2NrLlxuICpcbiAqIFJlZ2lzdGVycyBhIG5ldyBibG9jayBwcm92aWRlZCBhIHVuaXF1ZSBuYW1lIGFuZCBhbiBvYmplY3QgZGVmaW5pbmcgaXRzXG4gKiBiZWhhdmlvci4gT25jZSByZWdpc3RlcmVkLCB0aGUgYmxvY2sgaXMgbWFkZSBlZGl0b3IgYXMgYW4gb3B0aW9uIHRvIGFueVxuICogZWRpdG9yIGludGVyZmFjZSB3aGVyZSBibG9ja3MgYXJlIGltcGxlbWVudGVkLlxuICpcbiAqIEBsaW5rIGh0dHBzOi8vd29yZHByZXNzLm9yZy9ndXRlbmJlcmcvaGFuZGJvb2svYmxvY2stYXBpL1xuICogQHBhcmFtICB7c3RyaW5nfSAgIG5hbWUgICAgIEJsb2NrIG5hbWUuXG4gKiBAcGFyYW0gIHtPYmplY3R9ICAgc2V0dGluZ3MgQmxvY2sgc2V0dGluZ3MuXG4gKiBAcmV0dXJuIHs/V1BCbG9ja30gICAgICAgICAgVGhlIGJsb2NrLCBpZiBpdCBoYXMgYmVlbiBzdWNjZXNzZnVsbHlcbiAqICAgICAgICAgICAgICAgICAgICAgICAgICAgICByZWdpc3RlcmVkOyBvdGhlcndpc2UgYHVuZGVmaW5lZGAuXG4gKi9cblxucmVnaXN0ZXJCbG9ja1R5cGUoJ2tieC9ibG9jay1rbm93bGVkZ2ViYXNlLWJsb2NrcycsIHtcblx0Ly8gQmxvY2sgbmFtZS4gQmxvY2sgbmFtZXMgbXVzdCBiZSBzdHJpbmcgdGhhdCBjb250YWlucyBhIG5hbWVzcGFjZSBwcmVmaXguIEV4YW1wbGU6IG15LXBsdWdpbi9teS1jdXN0b20tYmxvY2suXG5cdHRpdGxlOiBfXygnS25vd2xlZGdlYmFzZSBIZWxwZGVzayDigJQgU2hvcnRjb2RlIEdlbmVyYXRvcicpLCAvLyBCbG9jayB0aXRsZS5cblx0aWNvbjogJ3NoaWVsZCcsIC8vIEJsb2NrIGljb24gZnJvbSBEYXNoaWNvbnMg4oaSIGh0dHBzOi8vZGV2ZWxvcGVyLndvcmRwcmVzcy5vcmcvcmVzb3VyY2UvZGFzaGljb25zLy5cblx0Y2F0ZWdvcnk6ICdjb21tb24nLCAvLyBCbG9jayBjYXRlZ29yeSDigJQgR3JvdXAgYmxvY2tzIHRvZ2V0aGVyIGJhc2VkIG9uIGNvbW1vbiB0cmFpdHMgRS5nLiBjb21tb24sIGZvcm1hdHRpbmcsIGxheW91dCB3aWRnZXRzLCBlbWJlZC5cblx0a2V5d29yZHM6IFtfXygnS25vd2xlZGdlYmFzZSBCbG9ja3MnKSwgX18oJ0tub3dsZWRnZWJhc2UgSGVscGRlc2snKSwgX18oJ0tub3dsZWRnZWJhc2UgSGVscGRlc2sgLSBTaG9ydGNvZGUgR2VuZXJhdG9yJyldLFxuXHRhdHRyaWJ1dGVzOiB7XG5cdFx0c2hvcnRjb2RlOiB7XG5cdFx0XHR0eXBlOiAnc3RyaW5nJyxcblx0XHRcdGRlZmF1bHQ6ICcnXG5cdFx0fVxuXHR9LFxuXG5cdC8qKlxuICAqIFRoZSBlZGl0IGZ1bmN0aW9uIGRlc2NyaWJlcyB0aGUgc3RydWN0dXJlIG9mIHlvdXIgYmxvY2sgaW4gdGhlIGNvbnRleHQgb2YgdGhlIGVkaXRvci5cbiAgKiBUaGlzIHJlcHJlc2VudHMgd2hhdCB0aGUgZWRpdG9yIHdpbGwgcmVuZGVyIHdoZW4gdGhlIGJsb2NrIGlzIHVzZWQuXG4gICpcbiAgKiBUaGUgXCJlZGl0XCIgcHJvcGVydHkgbXVzdCBiZSBhIHZhbGlkIGZ1bmN0aW9uLlxuICAqXG4gICogQGxpbmsgaHR0cHM6Ly93b3JkcHJlc3Mub3JnL2d1dGVuYmVyZy9oYW5kYm9vay9ibG9jay1hcGkvYmxvY2stZWRpdC1zYXZlL1xuICAqL1xuXHRlZGl0OiBmdW5jdGlvbiBlZGl0KHByb3BzKSB7XG5cdFx0dmFyIHNob3J0Y29kZSA9IHByb3BzLmF0dHJpYnV0ZXMuc2hvcnRjb2RlLFxuXHRcdCAgICBzZXRBdHRyaWJ1dGVzID0gcHJvcHMuc2V0QXR0cmlidXRlcztcblxuXG5cdFx0ZnVuY3Rpb24gc2hvd1Nob3J0Y29kZU1vZGFsKGUpIHtcblx0XHRcdGpRdWVyeSgnI2tieF9zaG9ydGNvZGVfZ2VuZXJhdG9yX21ldGFfYmxvY2snKS5wcm9wKCdkaXNhYmxlZCcsIHRydWUpO1xuXHRcdFx0alF1ZXJ5KGUudGFyZ2V0KS5hZGRDbGFzcygnY3VycmVudGx5X2VkaXRpbmcnKTtcblx0XHRcdGpRdWVyeS5wb3N0KGFqYXh1cmwsIHtcblx0XHRcdFx0YWN0aW9uOiAnc2hvd19rYnhfc2hvcnRjb2RlX2NtbidcblxuXHRcdFx0fSwgZnVuY3Rpb24gKGRhdGEpIHtcblx0XHRcdFx0alF1ZXJ5KCcjd3B3cmFwJykuYXBwZW5kKGRhdGEpO1xuXHRcdFx0fSk7XG5cdFx0fVxuXG5cdFx0ZnVuY3Rpb24gaW5zZXJ0U2hvcnRDb2RlKGUpIHtcblx0XHRcdHZhciBzaG9ydGNvZGVEYXRhID0galF1ZXJ5KCcja2J4X2FkZF9zaG9ydGNvZGVfY21uJykuYXR0cignZ3V0ZW5iZXJnX2tieF9zaG9ydGNvZGVfZ2VuZXJhdG9yX3ZhbHVlJyk7XG5cdFx0XHRzZXRBdHRyaWJ1dGVzKHsgc2hvcnRjb2RlOiBzaG9ydGNvZGVEYXRhIH0pO1xuXHRcdFx0alF1ZXJ5KCcja2J4X2FkZF9zaG9ydGNvZGVfY21uJykucGFyZW50cygnI3NtLW1vZGFsJykucmVtb3ZlKCk7XG5cdFx0XHRqUXVlcnkoJy5jdXJyZW50bHlfZWRpdGluZycpLnJlbW92ZUNsYXNzKCdjdXJyZW50bHlfZWRpdGluZycpO1xuXHRcdFx0alF1ZXJ5KCcja2J4X3Nob3J0Y29kZV9nZW5lcmF0b3JfbWV0YV9ibG9jaycpLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJyk7XG5cdFx0XHRjb25zb2xlLmxvZyh7IHNob3J0Y29kZTogc2hvcnRjb2RlIH0pO1xuXHRcdH1cblxuXHRcdGpRdWVyeShkb2N1bWVudCkub24oJ2NsaWNrJywgJyNrYnhfYWRkX3Nob3J0Y29kZV9jbW4nLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHQvL2UucHJldmVudERlZmF1bHQoKTtcblx0XHRcdGpRdWVyeSgnLmN1cnJlbnRseV9lZGl0aW5nJykubmV4dCgnI2luc2VydF9rYnhfc2hvcnRjb2RlJykudHJpZ2dlcignY2xpY2snKTtcblx0XHR9KTtcblxuXHRcdGZ1bmN0aW9uIGdldFNob3J0Q29kZSgpIHtcblx0XHRcdGpRdWVyeShkb2N1bWVudCkuZmluZCgnI2tieF9hZGRfc2hvcnRjb2RlX2NtbicpLnRyaWdnZXIoJ2NsaWNrJyk7XG5cdFx0XHQvL2pRdWVyeShkb2N1bWVudCkuZmluZCggJy5tb2RhbC1jb250ZW50IC5jbG9zZScpLnRyaWdnZXIoJ2NsaWNrJyk7XG5cdFx0fVxuXHRcdGpRdWVyeShkb2N1bWVudCkub24oJ2NsaWNrJywgJy5tb2RhbC1jb250ZW50IC5jbG9zZScsIGZ1bmN0aW9uICgpIHtcblx0XHRcdGpRdWVyeSgnLmN1cnJlbnRseV9lZGl0aW5nJykucmVtb3ZlQ2xhc3MoJ2N1cnJlbnRseV9lZGl0aW5nJyk7XG5cdFx0XHRqUXVlcnkoJyNrYnhfc2hvcnRjb2RlX2dlbmVyYXRvcl9tZXRhX2Jsb2NrJykucmVtb3ZlQXR0cignZGlzYWJsZWQnKTtcblx0XHR9KTtcblxuXHRcdHJldHVybiB3cC5lbGVtZW50LmNyZWF0ZUVsZW1lbnQoXG5cdFx0XHQnZGl2Jyxcblx0XHRcdHsgY2xhc3NOYW1lOiBwcm9wcy5jbGFzc05hbWUgfSxcblx0XHRcdHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudCgnaW5wdXQnLCB7IHR5cGU6ICdidXR0b24nLCBpZDogJ2tieF9zaG9ydGNvZGVfZ2VuZXJhdG9yX21ldGFfYmxvY2snLCBvbkNsaWNrOiBzaG93U2hvcnRjb2RlTW9kYWwsIGNsYXNzTmFtZTogJ2J1dHRvbiBidXR0b24tcHJpbWFyeSBidXR0b24tbGFyZ2UnLCB2YWx1ZTogJ0dlbmVyYXRlIEtub3dsZWRnZWJhc2UgU2hvcnRjb2RlJyB9KSxcblx0XHRcdHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudCgnaW5wdXQnLCB7IHR5cGU6ICdidXR0b24nLCBpZDogJ2luc2VydF9rYnhfc2hvcnRjb2RlJywgb25DbGljazogaW5zZXJ0U2hvcnRDb2RlLCBjbGFzc05hbWU6ICdidXR0b24gYnV0dG9uLXByaW1hcnkgYnV0dG9uLWxhcmdlIGd1dGVuYmVyZ19oaWRkZW4nLCB2YWx1ZTogJ1Rlc3QgS25vd2xlZGdlYmFzZSBTaG9ydGNvZGUnIH0pLFxuXHRcdFx0d3AuZWxlbWVudC5jcmVhdGVFbGVtZW50KCdpbnB1dCcsIHsgdHlwZTogJ2J1dHRvbicsIGlkOiAnZ2V0X2tieF9zaG9ydGNvZGUnLCBvbkNsaWNrOiBnZXRTaG9ydENvZGUsIGNsYXNzTmFtZTogJ2J1dHRvbiBidXR0b24tcHJpbWFyeSBidXR0b24tbGFyZ2UgZ3V0ZW5iZXJnX2hpZGRlbicsIHZhbHVlOiAnR2V0IEtub3dsZWRnZWJhc2UgU2hvcnRjb2RlJyB9KSxcblx0XHRcdHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudCgnYnInLCBudWxsKSxcblx0XHRcdHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChcblx0XHRcdFx0J2RpdicsXG5cdFx0XHRcdHsgY2xhc3NOYW1lOiAna2J4X3Nob3J0Y29kZV92YWx1ZScgfSxcblx0XHRcdFx0c2hvcnRjb2RlXG5cdFx0XHQpXG5cdFx0KTtcblx0fSxcblxuXHQvKipcbiAgKiBUaGUgc2F2ZSBmdW5jdGlvbiBkZWZpbmVzIHRoZSB3YXkgaW4gd2hpY2ggdGhlIGRpZmZlcmVudCBhdHRyaWJ1dGVzIHNob3VsZCBiZSBjb21iaW5lZFxuICAqIGludG8gdGhlIGZpbmFsIG1hcmt1cCwgd2hpY2ggaXMgdGhlbiBzZXJpYWxpemVkIGJ5IEd1dGVuYmVyZyBpbnRvIHBvc3RfY29udGVudC5cbiAgKlxuICAqIFRoZSBcInNhdmVcIiBwcm9wZXJ0eSBtdXN0IGJlIHNwZWNpZmllZCBhbmQgbXVzdCBiZSBhIHZhbGlkIGZ1bmN0aW9uLlxuICAqXG4gICogQGxpbmsgaHR0cHM6Ly93b3JkcHJlc3Mub3JnL2d1dGVuYmVyZy9oYW5kYm9vay9ibG9jay1hcGkvYmxvY2stZWRpdC1zYXZlL1xuICAqL1xuXHRzYXZlOiBmdW5jdGlvbiBzYXZlKHByb3BzKSB7XG5cdFx0dmFyIHNob3J0Y29kZSA9IHByb3BzLmF0dHJpYnV0ZXMuc2hvcnRjb2RlO1xuXG5cdFx0cmV0dXJuIHdwLmVsZW1lbnQuY3JlYXRlRWxlbWVudChcblx0XHRcdCdkaXYnLFxuXHRcdFx0bnVsbCxcblx0XHRcdHNob3J0Y29kZVxuXHRcdCk7XG5cdH1cbn0pO1xuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL2Jsb2NrL2Jsb2NrLmpzXG4vLyBtb2R1bGUgaWQgPSAxXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///1\n");

/***/ }),
/* 2 */
/*!******************************!*\
  !*** ./src/block/style.scss ***!
  \******************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMi5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9jay9zdHlsZS5zY3NzPzgwZjMiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9ibG9jay9zdHlsZS5zY3NzXG4vLyBtb2R1bGUgaWQgPSAyXG4vLyBtb2R1bGUgY2h1bmtzID0gMCJdLCJtYXBwaW5ncyI6IkFBQUEiLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///2\n");

/***/ }),
/* 3 */
/*!*******************************!*\
  !*** ./src/block/editor.scss ***!
  \*******************************/
/*! dynamic exports provided */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiMy5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL3NyYy9ibG9jay9lZGl0b3Iuc2Nzcz80OWQyIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvYmxvY2svZWRpdG9yLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiQUFBQSIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///3\n");

/***/ })
/******/ ]);