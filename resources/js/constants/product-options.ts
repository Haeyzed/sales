/**
 * Product Form Constants
 *
 * Contains all combobox options and constants used in product forms.
 */

export const PRODUCT_TYPES = [
    { value: 'standard', label: 'Standard' },
    { value: 'combo', label: 'Combo' },
    { value: 'digital', label: 'Digital' },
    { value: 'service', label: 'Service' },
] as const;

export const BARCODE_SYMBOLOGIES = [
    { value: 'C128', label: 'Code 128' },
    { value: 'C39', label: 'Code 39' },
    { value: 'UPCA', label: 'UPC-A' },
    { value: 'UPCE', label: 'UPC-E' },
    { value: 'EAN8', label: 'EAN-8' },
    { value: 'EAN13', label: 'EAN-13' },
] as const;

export const TAX_METHODS = [
    { value: '1', label: 'Exclusive' },
    { value: '2', label: 'Inclusive' },
] as const;

export const WARRANTY_GUARANTEE_TYPES = [
    { value: 'days', label: 'Days' },
    { value: 'months', label: 'Months' },
    { value: 'years', label: 'Years' },
] as const;

export type ProductType = typeof PRODUCT_TYPES[number]['value'];
export type BarcodeSymbology = typeof BARCODE_SYMBOLOGIES[number]['value'];
export type TaxMethod = typeof TAX_METHODS[number]['value'];
export type WarrantyGuaranteeType = typeof WARRANTY_GUARANTEE_TYPES[number]['value'];

