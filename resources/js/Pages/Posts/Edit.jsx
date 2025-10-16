import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import Textarea from "@/Components/Textarea";
import { useForm } from "@inertiajs/react";
import { usePage } from "@inertiajs/react";
import PostForm from "@/Components/Posts/Form";

export default function EditPost({ post }) {
    const { flash } = usePage().props;

    const { data, setData, put, processing, errors } = useForm({
        title: post?.title ?? "",
        content: post?.content ?? "",
    });

    function submit(e) {
        e.preventDefault();
        put(route("post.update", post.id));
    }

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Edit Post
                </h2>
            }
        >
            <Head title="Edit Post" />
            <PostForm
                submit={submit}
                data={data}
                setData={setData}
                processing={processing}
                errors={errors}
                flash={flash}
                buttonText="Update"
            />
        </AuthenticatedLayout>
    );
}
