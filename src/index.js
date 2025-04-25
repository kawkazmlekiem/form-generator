import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, SelectControl, CheckboxControl, Button } from '@wordpress/components';
import { useState, Fragment } from '@wordpress/element';
import block from './block.json';

registerBlockType(block.name, {
    edit({ attributes, setAttributes }) {
        const { content = [], actionForm } = attributes;
        const [ input, setInput ] = useState({ name: '', type: 'text', className: '', required: false });
        const [ actionValue, setAction ] = useState(); 
        const [ error, setError ] = useState(false);
        const blockProps = useBlockProps();

        const updateField = (key, value) => {
            setInput({...input, [key] : value});
        };

        const addField = () => {
            if (!input.name || !input.className) {
                setError(true);
                return;
            }

            const newInput = [
                ...content,
                input
            ];
            setAttributes({ content: newInput });
            clearValue();
            setError(false);
        };

        const removeField = (index) => {
            const newInput = [...content];
            newInput.splice(index, 1);
            setAttributes({ content: newInput });
        };

        const addActionForm = (actionValue) => {
            setAction(actionValue);
            setAttributes({ actionForm: actionValue });
        }

        const clearValue = () => {
            setInput({ name: '', type: 'text', className: '', required: false });
        }

        return (
        <>
        <div {...blockProps}>
            <h2>Form Generator</h2>
            <p>Form main options</p>
                <TextControl
                        label="Action attribute"
                        value={actionForm}
                        onChange={(actionValue) => addActionForm(actionValue)}
                    />
                <Fragment>
                {content.map((field, index) => (
                    <div>
                        <p>Name:{field.name} Type:{field.type} Class name:{field.className} Required:{field.required ? 'true' : 'false'}</p> 
                        <Button variant='secondary' onClick={() => removeField(index)}>Delete</Button>
                    </div>
                ))}
                </Fragment>
                {error && (
                    <p style={{ color: 'red', fontWeight: 700 }}>
                        Please fill in all fields
                    </p>
                )}
                <Fragment>
                    <TextControl
                        label="Name"
                        value={input.name}
                        onChange={(val) => updateField('name', val)}
                    />
                    <SelectControl
                        label="Type"
                        value={input.type}
                        options={[
                            { label: 'Text', value: 'text' },
                            { label: 'Email', value: 'email' },
                            { label: 'Button', value: 'submit' },
                            { label: 'Textarea', value: 'textarea' }
                        ]}
                        onChange={(val) => updateField('type', val)}
                    />
                    <TextControl
                        label="Class name"
                        value={input.className}
                        onChange={(val) => updateField('className', val)}
                    />
                    <CheckboxControl
                        label="Required"
                        checked={input.required}
                        onChange={(val) => updateField('required', val)}
                    />
                    <Button variant="primary" onClick={() => addField()}>Add input</Button>
                </Fragment>
        
        </div>
        </>
        );
    },
    save() {
        // Block is rendered via PHP
        return null;
    }
})