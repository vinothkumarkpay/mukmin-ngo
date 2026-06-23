<style>
/* Field headings only — nested option labels use .radio-label / .dropdown-option-item */
.form-group > label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
    font-size: 14px;
}

.radio-group {
    display: flex;
    flex-wrap: wrap;
    gap: 20px 30px;
    margin-top: 8px;
}

.radio-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 500 !important;
    font-size: 14px;
    line-height: 1.2;
    color: #444;
    cursor: pointer;
    margin-bottom: 0;
}

.radio-label input[type="radio"] {
    width: 18px;
    height: 18px;
    margin: 0;
    flex-shrink: 0;
    accent-color: #d43c18;
    cursor: pointer;
}

.dropdown-option-item {
    display: flex;
    align-items: center;
    padding: 10px 16px;
    cursor: pointer;
    transition: background 0.2s ease;
    font-size: 14px;
    line-height: 1.2;
    color: #444;
}

.dropdown-option-item input[type="checkbox"],
.dropdown-option-item input[type="radio"] {
    margin: 0 12px 0 0;
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    accent-color: #d43c18;
    cursor: pointer;
}

.dropdown-option-item span {
    user-select: none;
}
</style>
