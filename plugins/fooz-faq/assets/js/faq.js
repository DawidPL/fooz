(function (wp) {
  var el = wp.element.createElement;
  var __ = wp.i18n.__;
  var registerBlockType = wp.blocks.registerBlockType;

  var RichText = wp.blockEditor.RichText;
  var InnerBlocks = wp.blockEditor.InnerBlocks;
  var useBlockProps = wp.blockEditor.useBlockProps;

  // CHILD: FAQ Item
  registerBlockType("fooz/faq-item", {
    title: __("FAQ Item", "fooz-faq-block"),
    parent: ["fooz/faq-accordion"],
    icon: "editor-help",
    category: "widgets",
    supports: { reusable: false, html: false },
    attributes: {
      question: { type: "string", source: "html", selector: ".fooz-faq__question" },
    },

    edit: function (props) {
      var attrs = props.attributes;
      var setAttributes = props.setAttributes;

      var blockProps = useBlockProps({
        className: "fooz-faq__item",
      });

      return el(
        "div",
        blockProps,
        el(
          "details",
          { open: true, className: "fooz-faq__details overflow-hidden" },
          el(
            "summary",
            { className: "fooz-faq__summary flex items-center gap-3 cursor-pointer px-4 py-3 font-bold" },
            el(RichText, {
              tagName: "span",
              className: "fooz-faq__question",
              value: attrs.question,
              onChange: function (val) {
                setAttributes({ question: val });
              },
              placeholder: __("Question:", "fooz-faq-block"),
              allowedFormats: [],
            })
          ),
          el(
            "div",
            { className: "fooz-faq__answer px-4 pb-4 text-base opacity-90" },
            el(InnerBlocks, {
              allowedBlocks: ["core/paragraph", "core/list"],
              template: [["core/paragraph", { placeholder: __("Answer:", "fooz-faq-block") }]],
              templateLock: false,
            })
          )
        )
      );
    },

    save: function (props) {
      var attrs = props.attributes;
      var blockProps = wp.blockEditor.useBlockProps.save({ className: "fooz-faq__item" });

      return el(
        "div",
        blockProps,
        el(
          "details",
          { className: "fooz-faq__details overflow-hidden" },
          el(
            "summary",
            { className: "fooz-faq__summary flex items-center gap-3 cursor-pointer px-4 py-3 font-bold outline-none" },
            el(RichText.Content, { tagName: "span", className: "fooz-faq__question", value: attrs.question })
          ),
          el("div", { className: "fooz-faq__answer px-4 pb-4 text-base" }, el(InnerBlocks.Content))
        )
      );
    },
  });

  // PARENT: FAQ Accordion
  registerBlockType("fooz/faq-accordion", {
    title: __("FAQ Accordion", "fooz-faq-block"),
    icon: "list-view",
    category: "widgets",
    supports: { html: false },
    attributes: {
      heading: { type: "string", source: "html", selector: ".fooz-faq__heading" },
    },

    edit: function (props) {
      var attrs = props.attributes;
      var setAttributes = props.setAttributes;

      var blockProps = useBlockProps({
        className: "fooz-faq mx-auto max-w-3xl px-4 py-6",
      });

      return el(
        "section",
        blockProps,
        el(RichText, {
          tagName: "h2",
          className: "fooz-faq__heading text-3xl font-bold text-center pb-5",
          value: attrs.heading,
          onChange: function (val) {
            setAttributes({ heading: val });
          },
          placeholder: __("FAQ", "fooz-faq-block"),
          allowedFormats: [],
        }),
        el(
          "div",
          { className: "fooz-faq__items mt-5 grid gap-3" },
          el(InnerBlocks, {
            allowedBlocks: ["fooz/faq-item"],
            template: [["fooz/faq-item"]],
            templateLock: false,
          })
        )
      );
    },

    save: function (props) {
      var attrs = props.attributes;
      var blockProps = wp.blockEditor.useBlockProps.save({
        className: "fooz-faq mx-auto max-w-3xl px-4 py-6",
      });

      return el(
        "section",
        blockProps,
        el(RichText.Content, {
          tagName: "h2",
          className: "fooz-faq__heading text-3xl font-bold text-center pb-5",
          value: attrs.heading,
        }),
        el("div", { className: "fooz-faq__items mt-5 grid gap-3" }, el(InnerBlocks.Content))
      );
    },
  });
})(window.wp);
