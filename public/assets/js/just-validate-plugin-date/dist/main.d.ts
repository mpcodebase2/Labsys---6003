declare type PluginValueType = string | boolean;
declare type PluginFieldsType = {
    [key: string]: any;
};
interface ConfigInterface {
    format?: string;
    isAfter?: string | Date;
    isAfterOrEqual?: string | Date;
    isBefore?: string | Date;
    isBeforeOrEqual?: string | Date;
    isEqual?: string | Date;
    required?: boolean;
}
declare const pluginDate: (func: (fields: PluginFieldsType) => ConfigInterface) => (value: PluginValueType, fields: PluginFieldsType) => boolean;
export default pluginDate;
