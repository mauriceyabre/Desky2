# # IssuedDocument

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**id** | **int** | Unique identifier of the document. | [optional]
**entity** | [**\FattureInCloud\Model\Entity**](Entity.md) |  | [optional]
**type** | [**\FattureInCloud\Model\IssuedDocumentType**](IssuedDocumentType.md) |  | [optional]
**number** | **int** | Number of the document [If not specified, next number is used] | [optional]
**numeration** | **string** | Numeration of the document [Not available if type&#x3D;delivery_note] | [optional]
**date** | **\DateTime** | Date of the document [If not specified, today date is used] | [optional]
**year** | **int** | Invoice year. | [optional]
**currency** | [**\FattureInCloud\Model\Currency**](Currency.md) |  | [optional]
**language** | [**\FattureInCloud\Model\Language**](Language.md) |  | [optional]
**subject** | **string** | Issued document subject. | [optional]
**visible_subject** | **string** | Issued document visible subject. | [optional]
**rc_center** | **string** | Revenue center [or cost center if type&#x3D;supplier_order]. | [optional]
**notes** | **string** | Issued document extra notes. | [optional]
**rivalsa** | **float** | \&quot;Rivalsa INPS\&quot; percentual value | [optional]
**cassa** | **float** | \&quot;Cassa previdenziale\&quot; percentual value | [optional]
**amount_cassa** | **float** | [Read Only] Cassa amount. | [optional] [readonly]
**cassa_taxable** | **float** | Cassa taxable percentage | [optional]
**amount_cassa_taxable** | **float** | [Can be set only if cassa_taxable is NULL] Cassa2 taxable amount | [optional]
**cassa2** | **float** | \&quot;Cassa previdenziale 2\&quot; percentual value | [optional]
**amount_cassa2** | **float** | [Read Only] Cassa amount. | [optional] [readonly]
**cassa2_taxable** | **float** | Cassa2 taxable percentage | [optional]
**amount_cassa2_taxable** | **float** | [Can be set only if cassa2_taxable is NULL] Cassa2 taxable amount | [optional]
**global_cassa_taxable** | **float** | Global cassa taxable percentage | [optional]
**amount_global_cassa_taxable** | **float** | [Can be set only if global_cassa_taxable is NULL] Global cassa taxable amount | [optional]
**withholding_tax** | **float** | Withholding tax (ritenuta d&#39;acconto) percentual value | [optional]
**withholding_tax_taxable** | **float** | Withholding tax taxable (imponibile) percentual value | [optional]
**other_withholding_tax** | **float** | Other withholding tax (altra ritenuta) percentual value | [optional]
**stamp_duty** | **float** | Stamp duty value [0 if not present] | [optional]
**payment_method** | [**\FattureInCloud\Model\PaymentMethod**](PaymentMethod.md) |  | [optional]
**use_split_payment** | **bool** | Use split payment | [optional]
**use_gross_prices** | **bool** | Use gross prices | [optional]
**e_invoice** | **bool** | Indicates if this is an e-invoice. | [optional]
**ei_data** | [**\FattureInCloud\Model\IssuedDocumentEiData**](IssuedDocumentEiData.md) |  | [optional]
**ei_cassa_type** | **string** | E-invoice cassa type | [optional]
**ei_cassa2_type** | **string** | E-invoice cassa2 type | [optional]
**ei_withholding_tax_causal** | **string** | E-invoice withholding tax causal | [optional]
**ei_other_withholding_tax_type** | **string** | E-invoice other withholding tax type | [optional]
**ei_other_withholding_tax_causal** | **string** | E-invoice other withholding tax causal | [optional]
**items_list** | [**\FattureInCloud\Model\IssuedDocumentItemsListItem[]**](IssuedDocumentItemsListItem.md) |  | [optional]
**payments_list** | [**\FattureInCloud\Model\IssuedDocumentPaymentsListItem[]**](IssuedDocumentPaymentsListItem.md) |  | [optional]
**template** | [**\FattureInCloud\Model\DocumentTemplate**](DocumentTemplate.md) |  | [optional]
**delivery_note_template** | [**\FattureInCloud\Model\DocumentTemplate**](DocumentTemplate.md) |  | [optional]
**acc_inv_template** | [**\FattureInCloud\Model\DocumentTemplate**](DocumentTemplate.md) |  | [optional]
**h_margins** | **int** | Horizontal margins. | [optional]
**v_margins** | **int** | Vertical margins. | [optional]
**show_payments** | **bool** | Shows the expiration dates of the payments on the document. | [optional]
**show_payment_method** | **bool** | Show the payment method details on the document. | [optional]
**show_totals** | [**\FattureInCloud\Model\ShowTotalsMode**](ShowTotalsMode.md) |  | [optional]
**show_paypal_button** | **bool** | Show paypal button | [optional]
**show_notification_button** | **bool** | Show notification button | [optional]
**show_tspay_button** | **bool** | Show ts pay button. | [optional]
**delivery_note** | **bool** |  | [optional]
**accompanying_invoice** | **bool** | Attach an accompanying invoice. | [optional]
**dn_number** | **int** | Number (for the attached delivery note). | [optional]
**dn_date** | **\DateTime** | Date (for the attached delivery note). | [optional]
**dn_ai_packages_number** | **string** | Number of packages (for the attached delivery note). | [optional]
**dn_ai_weight** | **string** | Weight (for the attached delivery note). | [optional]
**dn_ai_causal** | **string** | Causal (for the attached delivery note). | [optional]
**dn_ai_destination** | **string** | Destination (for the attached delivery note). | [optional]
**dn_ai_transporter** | **string** | Transporter (for the attached delivery note). | [optional]
**dn_ai_notes** | **string** | Notes (for the attached delivery note). | [optional]
**is_marked** | **bool** | This is true if the document is marked. | [optional]
**amount_net** | **float** | [Read Only] Total net amount (competenze). | [optional] [readonly]
**amount_vat** | **float** | [Read Only] Total vat amount (IVA). | [optional] [readonly]
**amount_gross** | **float** | [Read Only] Total gross amount (totale documento). | [optional] [readonly]
**amount_due_discount** | **float** | Amount due discount | [optional]
**amount_rivalsa** | **float** | [Read Only] Rivalsa amount. | [optional] [readonly]
**amount_rivalsa_taxable** | **float** | Taxable rivalsa amount | [optional]
**amount_withholding_tax** | **float** | [Read Only] Withholding tax amount (ritenuta d&#39;acconto). | [optional] [readonly]
**amount_withholding_tax_taxable** | **float** | Taxable withholding tax amount | [optional]
**amount_other_withholding_tax** | **float** | [Read Only] Other withholding tax amount (altra ritenuta). | [optional] [readonly]
**amount_other_withholding_tax_taxable** | **float** | Taxable other withholding tax amount | [optional]
**amount_enasarco_taxable** | **float** | Taxable enasarco amount | [optional]
**extra_data** | [**\FattureInCloud\Model\IssuedDocumentExtraData**](IssuedDocumentExtraData.md) |  | [optional]
**seen_date** | **\DateTime** | Date when the client/supplier has seen the document. | [optional]
**next_due_date** | **\DateTime** | Date of the next not paid payment. | [optional]
**url** | **string** | [Temporary] [Read Only]   Public url of the document PDF file. | [optional]
**dn_url** | **string** | [Temporary] [Read Only]   Public url of the attached delivery note PDF file. | [optional]
**ai_url** | **string** | [Temporary] [Read Only]   Public url of the accompanying invoice PDF file. | [optional]
**attachment_url** | **string** | [Temporary] [Read Only] Public url of the attached file. Authomatically set if a valid attachment token is passed via POST /issued_documents or PUT /issued_documents/{documentId}. | [optional] [readonly]
**attachment_token** | **string** | [Write Only] Attachment token returned by POST /issued_documents/attachment. Used to attach the file already uploaded. | [optional]
**ei_raw** | **object** | Advanced raw attributes for e-invoices. | [optional]
**ei_status** | **string** | [Read only] Status of the e-invoice.   * &#x60;attempt&#x60; - We are trying to send the invoice, please wait up to 2 hours   * &#x60;missing&#x60; - The invoice is missing   * &#x60;not_sent&#x60; - The invoice has yet to be sent   * &#x60;sent&#x60; - The invoice was sent   * &#x60;pending&#x60; - The checks for the digital signature and sending are in progress   * &#x60;processing&#x60; - The SDI is delivering the invoice to the customer   * &#x60;error&#x60; - An error occurred while handling the invoice, please try to resend it or contact support   * &#x60;discarded&#x60; - The invoice has been rejected by the SDI, so it must be corrected and re-sent   * &#x60;not_delivered&#x60; - The SDI was unable to deliver the invoice   * &#x60;accepted&#x60; - The customer accepted the invoice   * &#x60;rejected&#x60; - The customer rejected the invoice, so it must be corrected   * &#x60;no_response&#x60; - A response has not yet been received whithin the deadline, contact the customer to ascertain the status of the invoice   * &#x60;manual_accepted&#x60; - The customer accepted the invoice   * &#x60;manual_rejected&#x60; - The customer rejected the invoice | [optional]
**created_at** | **string** |  | [optional]
**updated_at** | **string** |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)