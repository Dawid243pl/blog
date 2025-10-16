import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import Textarea from "@/Components/Textarea";
import Flash from "@/Components/Flash";
import { Link } from "@inertiajs/react";

export default function PostForm({
    submit,
    data,
    setData,
    processing,
    errors,
    flash,
    buttonText = "Create",
}) {
    return (
        <div className="py-12">
            <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Link
                    href={route("post.index")}
                    method="get"
                    as="button"
                    className="mb-4 inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900"
                >
                    Back to Posts
                </Link>
                <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div className="p-6 text-gray-900">
                        <form onSubmit={submit}>
                            <div className="my-4">
                                <InputLabel htmlFor="title" value="Title" />

                                <TextInput
                                    id="title"
                                    name="title"
                                    value={data.title}
                                    className="mt-1 block w-full"
                                    autoComplete="title"
                                    isFocused={true}
                                    onChange={(e) =>
                                        setData("title", e.target.value)
                                    }
                                    required
                                />

                                <InputError
                                    message={errors.title}
                                    className="mt-2"
                                />
                            </div>
                            <div className="my-4">
                                <InputLabel htmlFor="content" value="Content" />

                                <Textarea
                                    id="content"
                                    name="content"
                                    value={data.content}
                                    className="mt-1 block w-full"
                                    autoComplete="content"
                                    isFocused={true}
                                    onChange={(e) =>
                                        setData("content", e.target.value)
                                    }
                                    required
                                />

                                <InputError
                                    message={errors.content}
                                    className="mt-2"
                                />
                            </div>
                            <PrimaryButton
                                className="mt-4"
                                type="submit"
                                disabled={processing}
                            >
                                {buttonText}
                            </PrimaryButton>
                        </form>
                    </div>
                </div>
                <Flash flash={flash} />
            </div>
        </div>
    );
}
