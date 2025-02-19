require('dotenv').config();
const express = require('express');
const nodemailer = require('nodemailer');
const cors = require('cors');
const bodyParser = require('body-parser');

const app = express();
app.use(cors());
app.use(bodyParser.json());

// Configure Nodemailer
const transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: process.env.EMAIL_USER,
        pass: process.env.EMAIL_PASS
    }
});

app.post('/send-email', async (req, res) => {
    const { fname, lname, email, subject, message } = req.body;

    const mailOptions = {
        from: process.env.EMAIL_USER,
        to: 'recipient@gmail.com', // Change to your recipient email
        subject: `New Contact Message: ${subject}`,
        text: `From: ${fname} ${lname} (${email})\n\n${message}`
    };

    try {
        await transporter.sendMail(mailOptions);
        res.json({ message: 'Email sent successfully!' });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Failed to send email' });
    }
});
å
app.listen(3000, () => console.log('Server running on port 3000'));
