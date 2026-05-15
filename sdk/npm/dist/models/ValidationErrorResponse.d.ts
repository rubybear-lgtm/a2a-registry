export type ValidationErrorResponse = {
    message: string;
    errors: Record<string, Array<string>>;
};
