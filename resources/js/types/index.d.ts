export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>
> = T & {
    auth: {
        user: User;
    };
};

export type Pagination = {
    total: number;
    from: number;
    links: Array<{
        url: string;
        active: boolean;
        label: string;
    }>;
};

export type TableColumn = Array<{
    header: string;
    value: string | Function;
    className?: string;
}>;
