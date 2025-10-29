<x-mail::message>
    # New Contact Form Submission

    You have received a new message from your contact form.

    **Name:** {{ $name }}
    **Email:** {{ $email }}
    **Subject:** {{ $subject }}

    ## Message

    {{ $message }}

    ---

    You can reply directly to this email to respond to {{ $name }}.
</x-mail::message>
